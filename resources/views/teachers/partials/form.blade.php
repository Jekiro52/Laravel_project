<div class="row g-3">
    <div class="col-md-6">
        <label for="first_name" class="form-label">First Name</label>
        <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $teacher?->first_name) }}" class="form-control @error('first_name') is-invalid @enderror" required>
        @error('first_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="middle_name" class="form-label">Middle Name</label>
        <input type="text" name="middle_name" id="middle_name" value="{{ old('middle_name', $teacher?->middle_name) }}" class="form-control @error('middle_name') is-invalid @enderror">
        @error('middle_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="last_name" class="form-label">Last Name</label>
        <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $teacher?->last_name) }}" class="form-control @error('last_name') is-invalid @enderror" required>
        @error('last_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="username" class="form-label">Username</label>
        <input type="text" name="username" id="username" value="{{ old('username', $teacher?->username) }}" class="form-control @error('username') is-invalid @enderror" required>
        @error('username')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" id="email" value="{{ old('email', $teacher?->email) }}" class="form-control @error('email') is-invalid @enderror" required>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="contact" class="form-label">Contact Number</label>
        <input type="text" name="contact" id="contact" value="{{ old('contact', $teacher?->contact) }}" class="form-control @error('contact') is-invalid @enderror" required>
        @error('contact')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="password" class="form-label">{{ $teacher ? 'New Password' : 'Temporary Password' }}</label>
        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" {{ $teacher ? '' : 'required' }}>
        <div class="form-text text-muted">{{ $teacher ? 'Leave blank to keep the current password.' : 'Teacher can use this password to log in.' }}</div>
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    @if ($teacher)
        <div class="col-md-6 d-flex align-items-end">
            <div class="form-check">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" id="is_active" value="1" class="form-check-input" @checked(old('is_active', $teacher->is_active))>
                <label for="is_active" class="form-check-label">Active account</label>
            </div>
        </div>
    @endif
</div>
