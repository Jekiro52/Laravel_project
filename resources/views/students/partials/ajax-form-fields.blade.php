<div class="row g-3">
    <div class="col-md-4 student-field">
        <label for="{{ $formPrefix }}_first_name" class="form-label">First Name</label>
        <input type="text" name="first_name" id="{{ $formPrefix }}_first_name" class="form-control" required>
        <div class="invalid-feedback"></div>
    </div>
    <div class="col-md-4 student-field">
        <label for="{{ $formPrefix }}_middle_name" class="form-label">Middle Name</label>
        <input type="text" name="middle_name" id="{{ $formPrefix }}_middle_name" class="form-control">
        <div class="invalid-feedback"></div>
    </div>
    <div class="col-md-4 student-field">
        <label for="{{ $formPrefix }}_last_name" class="form-label">Last Name</label>
        <input type="text" name="last_name" id="{{ $formPrefix }}_last_name" class="form-control" required>
        <div class="invalid-feedback"></div>
    </div>
    <div class="col-12 student-field">
        <label for="{{ $formPrefix }}_address" class="form-label">Address</label>
        <textarea name="address" id="{{ $formPrefix }}_address" rows="3" class="form-control" required></textarea>
        <div class="invalid-feedback"></div>
    </div>
    <div class="col-md-6 student-field">
        <label for="{{ $formPrefix }}_contact" class="form-label">Contact</label>
        <input type="text" name="contact" id="{{ $formPrefix }}_contact" class="form-control" required>
        <div class="invalid-feedback"></div>
    </div>
    <div class="col-md-6 student-field">
        <label for="{{ $formPrefix }}_email" class="form-label">Email</label>
        <input type="email" name="email" id="{{ $formPrefix }}_email" class="form-control" required>
        <div class="invalid-feedback"></div>
    </div>
    <div class="col-12 student-field">
        <label for="{{ $formPrefix }}_degree_id" class="form-label">Degree</label>
        <select name="degree_id" id="{{ $formPrefix }}_degree_id" class="form-select" required>
            <option value="">Select Degree</option>
            @foreach ($degrees as $degree)
                <option value="{{ $degree->id }}">{{ $degree->title }}</option>
            @endforeach
        </select>
        <div class="invalid-feedback"></div>
    </div>

    @if ($includeLoginFields)
        <div class="col-md-6 student-field">
            <label for="{{ $formPrefix }}_username" class="form-label">Student Login Username</label>
            <input type="text" name="username" id="{{ $formPrefix }}_username" class="form-control">
            <div class="form-text text-muted">Fill this together with the password below to create the student's login account.</div>
            <div class="invalid-feedback"></div>
        </div>
        <div class="col-md-6 student-field">
            <label for="{{ $formPrefix }}_password" class="form-label">Temporary Password</label>
            <input type="password" name="password" id="{{ $formPrefix }}_password" class="form-control">
            <div class="form-text text-muted">The student will be asked to change this after first login.</div>
            <div class="invalid-feedback"></div>
        </div>
    @endif
</div>
