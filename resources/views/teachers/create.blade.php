@extends('layouts.app')

@section('title', 'Create Teacher')
@section('subtitle', 'Add a teacher login account.')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-body p-4">
                <form action="{{ route('teachers.store') }}" method="POST">
                    @csrf

                    @include('teachers.partials.form', ['teacher' => null])

                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Create Teacher</button>
                        <a href="{{ route('teachers.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
