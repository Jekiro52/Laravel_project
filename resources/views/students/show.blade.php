@extends('layouts.app')

@section('title', $student->full_name)
@section('subtitle', 'Review profile information and enrollment details.')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body p-4">
                <ul class="list-group list-group-flush mb-4">
                    <li class="list-group-item"><strong>Name:</strong> {{ $student->full_name }}</li>
                    <li class="list-group-item"><strong>Email:</strong> {{ $student->email }}</li>
                    <li class="list-group-item"><strong>Contact:</strong> {{ $student->contact }}</li>
                    <li class="list-group-item"><strong>Address:</strong> {{ $student->address }}</li>
                    <li class="list-group-item"><strong>Degree:</strong> {{ $student->degree->title }}</li>
                </ul>

                <div class="d-flex gap-2">
                    <a href="{{ route('students.edit', $student) }}" class="btn btn-primary">Edit</a>
                    <a href="{{ route('students.index') }}" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
