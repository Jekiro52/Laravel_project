@extends('layouts.app')

@section('title', 'Degrees')
@section('subtitle', 'Track degree programs and enrolled students.')

@section('page_actions')
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createDegreeModal">Add Degree</button>
@endsection

@section('content')
<div id="degreeAlert" class="alert d-none" role="alert"></div>

<div class="card">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Degree</th>
                    <th>Students</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody id="degreesTableBody">
                <tr>
                    <td colspan="3" class="text-center py-4 text-muted">Loading degrees...</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div id="degreesPagination" class="d-flex flex-wrap justify-content-between align-items-center gap-2 px-3 pb-3"></div>
</div>

<div id="degreesEmptyState" class="empty-card d-none flex-wrap justify-content-between align-items-center gap-3 mt-3">
    <div>
        <h3 class="h5 mb-1 text-dark">No degrees found.</h3>
        <p class="mb-0">Create a degree program to populate this page.</p>
    </div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createDegreeModal">Add Degree</button>
</div>

<div class="modal fade" id="viewDegreeModal" tabindex="-1" aria-labelledby="viewDegreeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content bg-dark text-light">
            <div class="modal-header border-secondary">
                <h2 class="modal-title h5" id="viewDegreeModalLabel">View Degree</h2>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="list-group list-group-flush mb-3">
                    <div class="list-group-item"><strong>Degree</strong><span id="viewDegreeTitle" class="d-block mt-1"></span></div>
                    <div class="list-group-item"><strong>Students</strong><span id="viewDegreeCount" class="d-block mt-1"></span></div>
                </div>
                <div id="viewDegreeStudents" class="list-group list-group-flush"></div>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createDegreeModal" tabindex="-1" aria-labelledby="createDegreeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-light">
            <form id="createDegreeForm" action="{{ route('degrees.store') }}" method="POST">
                @csrf
                <div class="modal-header border-secondary">
                    <h2 class="modal-title h5" id="createDegreeModalLabel">Add Degree</h2>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="degree-field">
                        <label for="create_degree_title" class="form-label">Degree Title</label>
                        <input type="text" name="title" id="create_degree_title" class="form-control" required>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Degree</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editDegreeModal" tabindex="-1" aria-labelledby="editDegreeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-light">
            <form id="editDegreeForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header border-secondary">
                    <h2 class="modal-title h5" id="editDegreeModalLabel">Edit Degree</h2>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="degree-field">
                        <label for="edit_degree_title" class="form-label">Degree Title</label>
                        <input type="text" name="title" id="edit_degree_title" class="form-control" required>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Degree</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    window.degreeAjaxConfig = {
        routes: {
            index: @json(route('degrees.index')),
        },
        csrfToken: @json(csrf_token()),
    };
</script>
<script src="{{ asset('js/app.js') }}"></script>
@endpush
