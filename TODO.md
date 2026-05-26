# Student & Degree CRUD App - Laravel 11 (Basic Eloquent, Tailwind)

## Progress: 13/18 ✅

### 1. Create TODO.md [DONE]

### 2. Generate Models & Migrations [DONE]
- `php artisan make:model Degree -m`
- `php artisan make:model Student -m`

### 3. Edit Migrations [DONE]
- database/migrations/2026_03_18_132137_create_degrees_table.php: add title column
- database/migrations/2026_03_18_132154_create_students_table.php: add columns (first_name, last_name, address, contact, email, degree_id foreign key)

### 4. Run Migrations [DONE]
- `php artisan migrate`

### 5. Edit Models [DONE]
- app/Models/Degree.php: $fillable, hasMany Students
- app/Models/Student.php: $fillable, belongsTo Degree

### 6. Generate Controllers [DONE]
- `php artisan make:controller DegreeController --resource --model=Degree`
- `php artisan make:controller StudentController --resource --model=Student`

### 7. Implement Controllers (basic CRUD) [DONE]
- DegreeController: index, create, store, show, edit, update, destroy
- StudentController: index(paginate w/ with('degree')), create/store/edit (degrees list), validation, success messages

### 8. Add Routes [DONE]
- routes/web.php: resource routes for degrees/students

### 9. Create Layout [DONE]
- resources/views/layouts/app.blade.php (Tailwind responsive nav, alerts, @yield content, dark mode)

### 10. Create Views - Degrees [DONE]
- resources/views/degrees/index.blade.php (responsive table, actions)
- resources/views/degrees/create.blade.php (form)
- resources/views/degrees/edit.blade.php (form)
- resources/views/degrees/show.blade.php (details)

### 11. Create Views - Students [DONE]
- resources/views/students/index.blade.php (table w/ pagination/degree name, actions)
- resources/views/students/create.blade.php (form w/ dropdown)
- resources/views/students/edit.blade.php (form w/ dropdown)
- resources/views/students/show.blade.php (details)

### 12. Update welcome.blade.php [DONE]
- Added nav links to Students/Degrees + logo

### 13. Build Assets [DONE]
- `npm install & npm run build`

### 14. Run Server
- `php artisan serve`

### 15-18. Test All CRUD + Pagination + Relations for Degrees & Students [Manual]

*Next step: Confirm artisan commands ready?*
