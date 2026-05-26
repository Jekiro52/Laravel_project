@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <p class="mb-4">Student and Degree Management System</p>
                <div class="d-flex gap-2">
                    <a href="{{ route('students.index') }}" class="btn btn-primary">Students</a>
                    <a href="{{ route('teachers.index') }}" class="btn btn-outline-primary">Teachers</a>
                    <a href="{{ route('degrees.index') }}" class="btn btn-outline-primary">Degrees</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
