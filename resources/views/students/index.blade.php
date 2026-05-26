@extends('layouts.app')

@section('title', 'Students')
@section('subtitle', 'Track student details and assigned degrees.')

@section('page_actions')
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createStudentModal">Add Student</button>
@endsection

@section('content')
<div id="studentAlert" class="alert d-none" role="alert"></div>

<div class="card">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Contact Number</th>
                    <th>Degree</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody id="studentsTableBody">
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">Loading students...</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div id="studentsPagination" class="d-flex flex-wrap justify-content-between align-items-center gap-2 px-3 pb-3"></div>
</div>

<div id="studentsEmptyState" class="empty-card d-none flex-wrap justify-content-between align-items-center gap-3 mt-3">
    <div>
        <h3 class="h5 mb-1 text-dark">No students found.</h3>
        <p class="mb-0">Add a student record to start building the list.</p>
    </div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createStudentModal">Add Student</button>
</div>

<div class="modal fade" id="viewStudentModal" tabindex="-1" aria-labelledby="viewStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content bg-dark text-light">
            <div class="modal-header border-secondary">
                <h2 class="modal-title h5" id="viewStudentModalLabel">View Student</h2>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item">
                        <strong>Name</strong>
                        <span id="viewStudentName" class="d-block mt-1"></span>
                    </div>
                    <div class="list-group-item">
                        <strong>Email</strong>
                        <span id="viewStudentEmail" class="d-block mt-1"></span>
                    </div>
                    <div class="list-group-item">
                        <strong>Contact Number</strong>
                        <span id="viewStudentContact" class="d-block mt-1"></span>
                    </div>
                    <div class="list-group-item">
                        <strong>Degree</strong>
                        <span id="viewStudentDegree" class="d-block mt-1"></span>
                    </div>
                    <div class="list-group-item">
                        <strong>Address</strong>
                        <span id="viewStudentAddress" class="d-block mt-1"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createStudentModal" tabindex="-1" aria-labelledby="createStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content bg-dark text-light">
            <form id="createStudentForm" action="{{ route('students.store') }}" method="POST">
                @csrf
                <div class="modal-header border-secondary">
                    <h2 class="modal-title h5" id="createStudentModalLabel">Add Student</h2>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @include('students.partials.ajax-form-fields', ['formPrefix' => 'create', 'student' => null, 'includeLoginFields' => true])
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Student</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editStudentModal" tabindex="-1" aria-labelledby="editStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content bg-dark text-light">
            <form id="editStudentForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header border-secondary">
                    <h2 class="modal-title h5" id="editStudentModalLabel">Edit Student</h2>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @include('students.partials.ajax-form-fields', ['formPrefix' => 'edit', 'student' => null, 'includeLoginFields' => false])
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Student</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    window.studentAjaxConfig = {
        routes: {
            index: @json(route('students.index')),
            degreesIndex: @json(route('degrees.index')),
        },
        csrfToken: @json(csrf_token()),
    };
</script>
<script src="{{ asset('js/app.js') }}"></script>
@endpush
