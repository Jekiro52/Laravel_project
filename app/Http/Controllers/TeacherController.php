<?php

namespace App\Http\Controllers;

use App\Models\UserAccounts;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TeacherController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $teachers = UserAccounts::query()
            ->where('role', 'teacher')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->orderBy('username')
            ->paginate(10);

        if ($request->expectsJson()) {
            return response()->json([
                'teachers' => $teachers->getCollection()
                    ->map(fn (UserAccounts $teacher) => $this->teacherPayload($teacher))
                    ->values(),
                'pagination' => $this->paginationPayload($teachers),
            ]);
        }

        return view('teachers.index', compact('teachers'));
    }

    public function create(): View
    {
        return view('teachers.create');
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $this->validateTeacher($request);

        $teacher = UserAccounts::query()->create([
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'last_name' => $validated['last_name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'contact' => $validated['contact'],
            'password' => Hash::make($validated['password']),
            'role' => 'teacher',
            'is_active' => true,
            'must_change_password' => false,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Teacher account successfully added. The teacher can now log in from the main login page.',
                'teacher' => $this->teacherPayload($teacher),
            ], 201);
        }

        return redirect()
            ->route('teachers.index')
            ->with('success', 'Teacher account successfully added. The teacher can now log in from the main login page.');
    }

    public function show(Request $request, UserAccounts $teacher): View|JsonResponse
    {
        $this->abortUnlessTeacher($teacher);

        if ($request->expectsJson()) {
            return response()->json([
                'teacher' => $this->teacherPayload($teacher),
            ]);
        }

        return view('teachers.edit', compact('teacher'));
    }

    public function edit(UserAccounts $teacher): View
    {
        $this->abortUnlessTeacher($teacher);

        return view('teachers.edit', compact('teacher'));
    }

    public function update(Request $request, UserAccounts $teacher): RedirectResponse|JsonResponse
    {
        $this->abortUnlessTeacher($teacher);

        $validated = $this->validateTeacher($request, $teacher);

        $teacher->forceFill([
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'last_name' => $validated['last_name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'contact' => $validated['contact'],
            'is_active' => (bool) ($validated['is_active'] ?? false),
        ]);

        if (! empty($validated['password'])) {
            $teacher->forceFill([
                'password' => Hash::make($validated['password']),
                'must_change_password' => false,
            ]);
        }

        $teacher->save();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Teacher account successfully updated.',
                'teacher' => $this->teacherPayload($teacher),
            ]);
        }

        return redirect()
            ->route('teachers.index')
            ->with('success', 'Teacher account successfully updated.');
    }

    public function destroy(Request $request, UserAccounts $teacher): RedirectResponse|JsonResponse
    {
        $this->abortUnlessTeacher($teacher);
        $teacher->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Teacher account successfully deleted.',
            ]);
        }

        return redirect()
            ->route('teachers.index')
            ->with('success', 'Teacher account successfully deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validateTeacher(Request $request, ?UserAccounts $teacher = null): array
    {
        $teacherId = $teacher?->id;

        return $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('user_accounts', 'username')->ignore($teacherId),
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('user_accounts', 'email')->ignore($teacherId),
            ],
            'contact' => ['required', 'string', 'max:30'],
            'password' => [
                $teacher ? 'nullable' : 'required',
                'string',
                'min:8',
            ],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }

    private function abortUnlessTeacher(UserAccounts $teacher): void
    {
        abort_unless($teacher->role === 'teacher', 404);
    }

    /**
     * @return array<string, mixed>
     */
    private function teacherPayload(UserAccounts $teacher): array
    {
        return [
            'id' => $teacher->id,
            'first_name' => $teacher->first_name,
            'middle_name' => $teacher->middle_name,
            'last_name' => $teacher->last_name,
            'display_name' => $teacher->display_name,
            'username' => $teacher->username,
            'email' => $teacher->email,
            'contact' => $teacher->contact,
            'is_active' => $teacher->is_active,
            'status' => $teacher->is_active ? 'Active' : 'Inactive',
            'show_url' => route('teachers.show', $teacher),
            'update_url' => route('teachers.update', $teacher),
            'delete_url' => route('teachers.destroy', $teacher),
        ];
    }

    /**
     * @return array<string, int|null>
     */
    private function paginationPayload(LengthAwarePaginator $paginator): array
    {
        return [
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
            'from' => $paginator->firstItem(),
            'to' => $paginator->lastItem(),
        ];
    }
}
