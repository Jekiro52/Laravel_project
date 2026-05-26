<?php

namespace App\Http\Middleware;

use App\Models\UserAccounts;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId = $request->session()->get('auth_user_id');
        $loginRoute = 'login';

        if (! $userId || ! $request->session()->get('auth_login_confirmed')) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route($loginRoute)->with('error', 'Please log in first.');
        }

        $user = UserAccounts::query()->find($userId);

        if (! $user || ! $user->is_active) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route($loginRoute)->with('error', 'Your session is no longer valid. Please log in again.');
        }

        $request->attributes->set('authUser', $user);
        $request->session()->put([
            'auth_username' => $user->username,
            'auth_user_role' => $user->role,
        ]);

        return $next($request);
    }
}
