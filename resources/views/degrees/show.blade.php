@extends('layouts.app')

@section('title', 'Degree Details')
@section('subtitle', 'View key metrics and summary information for this degree.')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-body p-4">
                <ul class="list-group list-group-flush mb-4">
                    <li class="list-group-item"><strong>Title:</strong> {{ $degree->title }}</li>
                    <li class="list-group-item"><strong>ID:</strong> {{ $degree->id }}</li>
                    <li class="list-group-item"><strong>Enrolled Students:</strong> {{ $degree->students->count() }}</li>
                </ul>

                @if ($degree->students->isNotEmpty())
                    <h2 class="h6 mb-3">Students In This Degree</h2>
                    <ul class="list-group list-group-flush mb-4">
                        @foreach ($degree->students as $student)
                            <li class="list-group-item d-flex flex-column flex-md-row justify-content-between gap-1">
                                <span>{{ $student->full_name }}</span>
                                <small>{{ $student->email }}</small>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="alert alert-info mb-4">No students are currently enrolled in this degree.</div>
                @endif

                <div class="d-flex gap-2">
                    <a href="{{ route('degrees.edit', $degree) }}" class="btn btn-primary">Edit</a>
                    <a href="{{ route('degrees.index') }}" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
