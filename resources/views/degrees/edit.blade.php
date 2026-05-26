@extends('layouts.app')

@section('title', 'Edit Degree')
@section('subtitle', 'Adjust program details and keep degree data clean.')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body p-4">
                <form action="{{ route('degrees.update', $degree) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="title" class="form-label">Degree Title</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $degree->title) }}" class="form-control @error('title') is-invalid @enderror" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Update Degree</button>
                        <a href="{{ route('degrees.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
