<div class="row g-3">
    <div class="col-md-6 teacher-field">
        <label for="{{ $formPrefix }}_teacher_first_name" class="form-label">First Name</label>
        <input type="text" name="first_name" id="{{ $formPrefix }}_teacher_first_name" class="form-control" required>
        <div class="invalid-feedback"></div>
    </div>
    <div class="col-md-6 teacher-field">
        <label for="{{ $formPrefix }}_teacher_middle_name" class="form-label">Middle Name</label>
        <input type="text" name="middle_name" id="{{ $formPrefix }}_teacher_middle_name" class="form-control">
        <div class="invalid-feedback"></div>
    </div>
    <div class="col-md-6 teacher-field">
        <label for="{{ $formPrefix }}_teacher_last_name" class="form-label">Last Name</label>
        <input type="text" name="last_name" id="{{ $formPrefix }}_teacher_last_name" class="form-control" required>
        <div class="invalid-feedback"></div>
    </div>
    <div class="col-md-6 teacher-field">
        <label for="{{ $formPrefix }}_teacher_username" class="form-label">Username</label>
        <input type="text" name="username" id="{{ $formPrefix }}_teacher_username" class="form-control" required>
        <div class="invalid-feedback"></div>
    </div>
    <div class="col-md-6 teacher-field">
        <label for="{{ $formPrefix }}_teacher_email" class="form-label">Email</label>
        <input type="email" name="email" id="{{ $formPrefix }}_teacher_email" class="form-control" required>
        <div class="invalid-feedback"></div>
    </div>
    <div class="col-md-6 teacher-field">
        <label for="{{ $formPrefix }}_teacher_contact" class="form-label">Contact Number</label>
        <input type="text" name="contact" id="{{ $formPrefix }}_teacher_contact" class="form-control" required>
        <div class="invalid-feedback"></div>
    </div>
    <div class="col-md-6 teacher-field">
        <label for="{{ $formPrefix }}_teacher_password" class="form-label">{{ $passwordRequired ? 'Temporary Password' : 'New Password' }}</label>
        <input type="password" name="password" id="{{ $formPrefix }}_teacher_password" class="form-control" {{ $passwordRequired ? 'required' : '' }}>
        <div class="form-text text-muted">{{ $passwordRequired ? 'Teacher can use this password to log in.' : 'Leave blank to keep the current password.' }}</div>
        <div class="invalid-feedback"></div>
    </div>

    @if ($includeStatus)
        <div class="col-md-6 teacher-field d-flex align-items-end">
            <div class="form-check">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" id="{{ $formPrefix }}_teacher_is_active" value="1" class="form-check-input">
                <label for="{{ $formPrefix }}_teacher_is_active" class="form-check-label">Active account</label>
            </div>
            <div class="invalid-feedback"></div>
        </div>
    @endif
</div>
