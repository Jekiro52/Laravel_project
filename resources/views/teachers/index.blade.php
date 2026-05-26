@extends('layouts.app')

@section('title', 'Teachers')
@section('subtitle', 'Create teacher login accounts that go directly to the welcome page.')

@section('page_actions')
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTeacherModal">Add Teacher</button>
@endsection

@section('content')
<div id="teacherAlert" class="alert d-none" role="alert"></div>

<div class="card">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Contact Number</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody id="teachersTableBody">
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">Loading teachers...</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div id="teachersPagination" class="d-flex flex-wrap justify-content-between align-items-center gap-2 px-3 pb-3"></div>
</div>

<div id="teachersEmptyState" class="empty-card d-none flex-wrap justify-content-between align-items-center gap-3 mt-3">
    <div>
        <h3 class="h5 mb-1 text-dark">No teachers found.</h3>
        <p class="mb-0">Add a teacher account so they can log in and open the welcome page.</p>
    </div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTeacherModal">Add Teacher</button>
</div>

<div class="modal fade" id="viewTeacherModal" tabindex="-1" aria-labelledby="viewTeacherModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content bg-dark text-light">
            <div class="modal-header border-secondary">
                <h2 class="modal-title h5" id="viewTeacherModalLabel">View Teacher</h2>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item"><strong>Name</strong><span id="viewTeacherName" class="d-block mt-1"></span></div>
                    <div class="list-group-item"><strong>Username</strong><span id="viewTeacherUsername" class="d-block mt-1"></span></div>
                    <div class="list-group-item"><strong>Email</strong><span id="viewTeacherEmail" class="d-block mt-1"></span></div>
                    <div class="list-group-item"><strong>Contact Number</strong><span id="viewTeacherContact" class="d-block mt-1"></span></div>
                    <div class="list-group-item"><strong>Status</strong><span id="viewTeacherStatus" class="d-block mt-1"></span></div>
                </div>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createTeacherModal" tabindex="-1" aria-labelledby="createTeacherModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content bg-dark text-light">
            <form id="createTeacherForm" action="{{ route('teachers.store') }}" method="POST">
                @csrf
                <div class="modal-header border-secondary">
                    <h2 class="modal-title h5" id="createTeacherModalLabel">Add Teacher</h2>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @include('teachers.partials.ajax-form-fields', ['formPrefix' => 'create', 'includeStatus' => false, 'passwordRequired' => true])
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Teacher</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editTeacherModal" tabindex="-1" aria-labelledby="editTeacherModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content bg-dark text-light">
            <form id="editTeacherForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header border-secondary">
                    <h2 class="modal-title h5" id="editTeacherModalLabel">Edit Teacher</h2>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @include('teachers.partials.ajax-form-fields', ['formPrefix' => 'edit', 'includeStatus' => true, 'passwordRequired' => false])
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Teacher</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    window.teacherAjaxConfig = {
        routes: {
            index: @json(route('teachers.index')),
        },
        csrfToken: @json(csrf_token()),
    };
</script>
<script src="{{ asset('js/app.js') }}"></script>
@endpush
