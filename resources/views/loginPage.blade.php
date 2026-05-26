@extends('auth.layout')

@section('title', 'Portal')
@section('heading', 'Access Portal')
@section('subheading', 'Use the main login page. The system will detect if the account is admin, teacher, or student.')

@section('content')
    <div class="vstack gap-3">
        <a href="{{ route('login') }}" class="btn btn-primary w-100">Open Login</a>
    </div>
@endsection
