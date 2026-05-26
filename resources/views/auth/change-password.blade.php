@extends('auth.layout')

@php
    $isStudent = $user->role === 'student';
    $isFirstTimeChange = $user->must_change_password;
@endphp

@section('title', $isStudent ? 'Student Change Password' : 'Change Password')

@section('heading')
    {{ $isStudent ? 'Update your student password' : 'Change your password' }}
@endsection

@section('subheading')
    {{ $isStudent
        ? 'Enter your current password first, then type and confirm your new password.'
        : 'Enter your current password, then create and confirm a new one.' }}
@endsection

@section('content')
    <div class="vstack gap-4">
        @if ($isStudent && $isFirstTimeChange)
            <span class="badge rounded-pill text-bg-primary align-self-start px-3 py-2 fs-6">Change Password First</span>
        @endif

        <form action="{{ route('password.change.update') }}" method="POST" class="vstack gap-3">
            @csrf

            <div>
                <label for="current_password" class="form-label">Current Password</label>
                <input
                    type="password"
                    name="current_password"
                    id="current_password"
                    class="form-control @error('current_password') is-invalid @enderror"
                    placeholder="Enter your current password"
                    required
                >
                @error('current_password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="password" class="form-label">New Password</label>
                <input
                    type="password"
                    name="password"
                    id="password"
                    class="form-control @error('password') is-invalid @enderror"
                    placeholder="Type your new password"
                    required
                >
                @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input
                    type="password"
                    name="password_confirmation"
                    id="password_confirmation"
                    class="form-control @error('password_confirmation') is-invalid @enderror"
                    placeholder="Confirm your new password"
                    required
                >
                @error('password_confirmation')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary w-100">
                {{ $isStudent ? 'Change Password' : 'Save New Password' }}
            </button>
        </form>
    </div>
@endsection
