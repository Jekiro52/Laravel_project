<?php

namespace App\Http\Middleware;

use App\Models\UserAccounts;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SessionUserAccount
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = null;
        $userId = $request->session()->get('auth_user_id');

        if ($userId && $request->session()->get('auth_login_confirmed')) {
            $user = UserAccounts::query()->find($userId);
        }

        if (! $user || ! $user->is_active) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->with('error', 'Please log in first.');
        }

        $request->attributes->set('authUser', $user);

        Session::put('logged_id', $user->id);
        Session::put('logged_user', $user->username);
        Session::put('logged_role', $user->role);

        $request->session()->put([
            'auth_username' => $user->username,
            'auth_user_role' => $user->role,
        ]);

        if ($user->must_change_password && ! $request->routeIs('student.password.*', 'password.change.*')) {
            $route = $user->role === 'student'
                ? 'student.password.form'
                : 'password.change.form';

            return redirect()
                ->route($route)
                ->with('info', 'You must change your password before accessing the dashboard.');
        }

        return $next($request);
    }
}
