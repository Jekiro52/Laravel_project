@extends('auth.student-portal-layout')

@section('title', 'Teacher Welcome')

@section('content')
    @php
        $displayName = $user->display_name;
    @endphp

    <div class="portal-panel">
        <span class="portal-badge">Teacher Web Page</span>

        <h2 class="portal-title">Welcome {{ $displayName }}</h2>
        <p class="portal-copy">Your teacher account is ready. You can now continue using the teacher portal.</p>
    </div>
@endsection
