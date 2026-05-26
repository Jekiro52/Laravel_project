@extends('layouts.app')

@section('title', 'Edit Student')
@section('subtitle', 'Update student details and keep records accurate.')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body p-4">
                <a href="{{ route('students.index') }}" class="btn btn-secondary">Back to Students</a>
            </div>
        </div>
    </div>
</div>
@endsection
