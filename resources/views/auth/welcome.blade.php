@extends('auth.layout')

@section('title', $user->role === 'admin' ? 'Admin Dashboard' : 'Welcome')

@section('heading')
    Welcome, {{ $user->username }}!
@endsection

@section('subheading')
    @if ($user->role === 'admin')
        Your admin account is ready. You can now manage students, degrees, and teachers.
    @else
        Your teacher account is ready. You can now continue from this welcome page.
    @endif
@endsection

@section('content')
    <div class="vstack gap-3">
        @if ($user->role === 'admin')
            <a href="{{ route('students.index') }}" class="btn btn-primary w-100">Go to Students</a>
            <a href="{{ route('teachers.index') }}" class="btn btn-outline-light w-100">Go to Teachers</a>
            <a href="{{ route('degrees.index') }}" class="btn btn-outline-light w-100">Go to Degrees</a>
            <a href="{{ route('password.change.form') }}" class="btn btn-outline-light w-100">Change Password Again</a>
        @endif

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-light w-100">Log Out</button>
        </form>
    </div>
@endsection
