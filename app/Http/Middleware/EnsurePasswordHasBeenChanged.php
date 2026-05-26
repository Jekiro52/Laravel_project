<?php

namespace App\Http\Middleware;

use App\Models\UserAccounts;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordHasBeenChanged
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->attributes->get('authUser');

        if (! $user instanceof UserAccounts) {
            $userId = $request->session()->get('auth_user_id');
            $user = $userId ? UserAccounts::query()->find($userId) : null;
        }

        if ($user?->must_change_password) {
            $route = $user->role === 'student'
                ? 'student.password.form'
                : 'password.change.form';

            $message = $user->role === 'student'
                ? 'Student login successful. Please change your password first.'
                : 'Please change your password before continuing.';

            return redirect()
                ->route($route)
                ->with('info', $message);
        }

        return $next($request);
    }
}
