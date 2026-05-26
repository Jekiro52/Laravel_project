<?php

namespace App\Http\Middleware;

use App\Models\UserAccounts;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->attributes->get('authUser');

        if (! $user instanceof UserAccounts) {
            $userId = $request->session()->get('auth_user_id');
            $user = $userId ? UserAccounts::query()->find($userId) : null;
        }

        if (! $user || ! in_array($user->role, $roles, true)) {
            $fallbackRoute = $user?->role === 'student' ? 'student.welcome' : 'welcome';

            return redirect()
                ->route($fallbackRoute)
                ->with('error', 'You are not allowed to access that page.');
        }

        return $next($request);
    }
}
