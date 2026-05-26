@extends('auth.student-guest-layout')

@section('title', 'Login')

@section('content')
    <div class="portal-head">
        <div>
            <p class="portal-kicker">Access Portal</p>
            <p class="portal-subkicker">Login</p>
        </div>
    </div>

    <h1 class="guest-title">Welcome</h1>
    <p class="guest-copy">Sign in using the username and password created for your account.</p>

    @if (session('success'))
        <div class="alert-box alert-success" role="alert">
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->has('username'))
        <div class="alert-box alert-danger" role="alert">
            <span>{{ $errors->first('username') }}</span>
        </div>
    @endif

    <form action="{{ route('login.attempt') }}" method="POST">
        @csrf

        <div class="field">
            <label for="username">Username</label>
            <input
                type="text"
                name="username"
                id="username"
                value="{{ old('username') }}"
                class="@error('username') is-invalid @enderror"
                required
                autofocus
            >
            @error('username')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="field">
            <label for="password">Password</label>
            <input
                type="password"
                name="password"
                id="password"
                class="@error('password') is-invalid @enderror"
                required
            >
            @error('password')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="submit-btn">Sign In</button>
    </form>

    <p class="helper-copy">
        Use the main login instead?
        <a href="{{ route('login') }}">Open login</a>
    </p>
@endsection
