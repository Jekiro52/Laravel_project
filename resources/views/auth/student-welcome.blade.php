@extends('auth.student-portal-layout')

@section('title', 'Student Welcome')

@section('content')
    @php
        $displayName = $user->students?->full_name ?? \Illuminate\Support\Str::title(
            str_replace(['.', '_', '-'], ' ', $user->username)
        );
    @endphp

    <div class="portal-panel">
        <span class="portal-badge">Student Web Page</span>

        <h2 class="portal-title">Welcome {{ $displayName }}</h2>
        <p class="portal-copy">Your student account is ready. You can now continue using the student portal with your updated credentials.</p>
    </div>
@endsection
