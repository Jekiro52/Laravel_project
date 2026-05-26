@extends('layouts.app')

@section('title', 'Edit Teacher')
@section('subtitle', 'Update teacher login details.')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-body p-4">
                <form action="{{ route('teachers.update', $teacher) }}" method="POST">
                    @csrf
                    @method('PUT')

                    @include('teachers.partials.form', ['teacher' => $teacher])

                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Update Teacher</button>
                        <a href="{{ route('teachers.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
