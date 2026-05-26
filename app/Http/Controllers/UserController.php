<?php

namespace App\Http\Controllers;

use App\Models\UserAccounts;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function showLoginForm(Request $request): View|RedirectResponse
    {
        if ($request->session()->has('auth_user_id')) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return view('auth.admin-login');
    }

    public function login(Request $request): RedirectResponse
    {
        return $this->authenticate($request);
    }

    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $login = trim($credentials['username']);
        $user = UserAccounts::query()
            ->where('username', $login)
            ->orWhere('email', $login)
            ->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return back()
                ->withErrors(['username' => 'Incorrect username or password.'])
                ->onlyInput('username');
        }

        if (! $user->is_active) {
            return back()
                ->withErrors(['username' => 'This account is inactive. Please contact the administrator.'])
                ->onlyInput('username');
        }

        $request->session()->regenerate();
        $request->session()->put([
            'auth_user_id' => $user->id,
            'auth_username' => $user->username,
            'auth_user_role' => $user->role,
            'auth_login_confirmed' => true,
        ]);

        return $this->redirectAfterLogin($user, true);
    }

    public function showChangePasswordForm(Request $request): View|RedirectResponse
    {
        $user = $this->authenticatedUser($request);

        abort_unless($user, 403);

        if ($user->role === 'student' && ! $request->routeIs('student.password.form')) {
            return redirect()->route('student.password.form');
        }

        $view = $user->role === 'student'
            ? 'auth.student-change-password'
            : 'auth.change-password';

        return view($view, compact('user'));
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $user = $this->authenticatedUser($request);

        abort_unless($user, 403);

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'different:current_password'],
            'password_confirmation' => ['required', 'same:password'],
        ], [
            'current_password.required' => 'Please enter your current password.',
            'password.required' => 'Please enter your new password.',
            'password.min' => 'Your new password must be at least 8 characters.',
            'password.different' => 'Your new password must be different from the current password.',
            'password_confirmation.required' => 'Please confirm your new password.',
            'password_confirmation.same' => 'Password not match.',
        ]);

        if (! Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors([
                'current_password' => 'Wrong password.',
            ]);
        }

        $user->forceFill([
            'password' => Hash::make($validated['password']),
            'must_change_password' => false,
        ])->save();

        if ($user->role === 'student') {
            return redirect()
                ->route('student.welcome')
                ->with('success', 'Password changed successfully. Welcome to the web page.');
        }

        return redirect()
            ->route('welcome')
            ->with('success', 'Password changed successfully.');
    }

    public function welcome(Request $request): View|RedirectResponse
    {
        $user = $this->authenticatedUser($request);

        abort_unless($user, 403);

        if ($user->role === 'student' && ! $request->routeIs('student.welcome')) {
            return redirect()->route('student.welcome');
        }

        if ($user->role === 'admin' && ! $request->routeIs('admin.dashboard')) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'teacher' && ! $request->routeIs('welcome', 'teacher.dashboard')) {
            return redirect()->route('teacher.dashboard');
        }

        if ($user->role === 'student') {
            $view = 'auth.student-welcome';
        } elseif ($user->role === 'admin') {
            $view = 'welcome';
        } else {
            $view = 'auth.teacher-welcome';
        }

        return view($view, compact('user'));
    }

    public function userAccounts(): View
    {
        $userAccounts = UserAccounts::query()
            ->with('students')
            ->orderByRaw("CASE role WHEN 'admin' THEN 1 WHEN 'teacher' THEN 2 WHEN 'student' THEN 3 ELSE 4 END")
            ->orderBy('username')
            ->get();

        return view('useraccounts.index', compact('userAccounts'));
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'You have been logged out.');
    }

    private function redirectToDashboard(?string $role): RedirectResponse
    {
        if ($role === 'teacher') {
            return redirect()->route('teacher.dashboard');
        }

        if ($role === 'admin') {
            return redirect()->route('useraccounts.index');
        }

        if ($role === 'student') {
            return redirect()->route('student.welcome');
        }

        return redirect()->route('login');
    }

    private function authenticatedUser(Request $request): ?UserAccounts
    {
        $userId = $request->session()->get('auth_user_id');

        if (! $userId) {
            return null;
        }

        return UserAccounts::query()->find($userId);
    }

    private function redirectAfterLogin(UserAccounts $user, bool $withSuccessMessage): RedirectResponse
    {
        if ($user->role === 'student') {
            if ($user->must_change_password) {
                return redirect()
                    ->route('student.password.form')
                    ->with('info', 'Student login successful. Please change your password first.');
            }

            return $this->redirectToDashboard($user->role);
        }

        if ($user->role === 'teacher') {
            return $this->redirectToDashboard($user->role);
        }

        if ($user->must_change_password) {
            return redirect()
                ->route('password.change.form')
                ->with('info', 'Login successful. Please change your password before continuing.');
        }

        $redirect = $user->role === 'admin'
            ? $this->redirectToDashboard($user->role)
            : redirect()->route('welcome');

        if ($withSuccessMessage) {
            $redirect->with('success', 'Successful login.');
        }

        return $redirect;
    }
}
