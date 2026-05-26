@extends('auth.student-portal-layout')

@section('title', 'Student Change Password')

@section('content')
    <div class="portal-panel">
        <span class="portal-badge">Change Password First</span>

        <h2 class="portal-title">Update your student password</h2>
        <p class="portal-copy">Enter your current password first, then type and confirm your new password.</p>

        <form action="{{ route('student.password.update') }}" method="POST">
            @csrf

            <div class="portal-field">
                <label for="current_password">Current Password</label>
                <input
                    type="password"
                    name="current_password"
                    id="current_password"
                    class="@error('current_password') is-invalid @enderror"
                    required
                >
                @error('current_password')
                    <span class="portal-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="portal-field">
                <label for="password">New Password</label>
                <input
                    type="password"
                    name="password"
                    id="password"
                    class="@error('password') is-invalid @enderror"
                    required
                >
                @error('password')
                    <span class="portal-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="portal-field">
                <label for="password_confirmation">Confirm Password</label>
                <input
                    type="password"
                    name="password_confirmation"
                    id="password_confirmation"
                    class="@error('password_confirmation') is-invalid @enderror"
                    required
                >
                @error('password_confirmation')
                    <span class="portal-error">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="portal-submit">Change Password</button>
        </form>
    </div>
@endsection
