@extends('auth.layout')

@section('title', 'Login')
@section('heading', 'Login')
@section('subheading', 'Sign in once. The system will detect if your account is admin, teacher, or student.')

@section('content')
    <form action="{{ route('login.attempt') }}" method="POST" class="vstack gap-3">
        @csrf

        <div>
            <label for="username" class="form-label">Email or Username</label>
            <input
                type="text"
                name="username"
                id="username"
                value="{{ old('username') }}"
                class="form-control @error('username') is-invalid @enderror"
                placeholder="Enter your email or username"
                required
                autofocus
            >
            @error('username')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="password" class="form-label">Password</label>
            <input
                type="password"
                name="password"
                id="password"
                class="form-control @error('password') is-invalid @enderror"
                placeholder="Enter your password"
                required
            >
            @error('password')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary w-100">Sign In</button>

        <p class="mb-0 text-center text-muted">
            Admin, teacher, and student accounts can use this login page.
        </p>
    </form>
@endsection
