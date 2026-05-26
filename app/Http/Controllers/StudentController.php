<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Student;
use App\Models\Degree;
use App\Models\UserAccounts;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|JsonResponse
    {
        $students = Student::query()
            ->with('degree')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->paginate(10);

        if ($request->expectsJson()) {
            return response()->json([
                'students' => $students->getCollection()
                    ->map(fn (Student $student) => $this->studentPayload($student))
                    ->values(),
                'pagination' => $this->paginationPayload($students),
            ]);
        }

        $degrees = Degree::query()
            ->orderBy('title')
            ->get();

        return view('students.index', compact('students', 'degrees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $degrees = Degree::query()
            ->orderBy('title')
            ->get();

        return view('students.create', compact('degrees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentRequest $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validated();

        $student = DB::transaction(function () use ($validated) {
            $userAccount = null;

            if (! empty($validated['username']) && ! empty($validated['password'])) {
                $userAccount = UserAccounts::query()->create([
                    'username' => $validated['username'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                    'must_change_password' => true,
                    'role' => 'student',
                ]);
            }

            return Student::query()->create([
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'] ?? null,
                'last_name' => $validated['last_name'],
                'address' => $validated['address'],
                'contact' => $validated['contact'],
                'email' => $validated['email'],
                'degree_id' => $validated['degree_id'],
                'user_account_id' => $userAccount?->id,
            ]);
        });

        Log::info('student.created', [
            'student_id' => $student->id,
            'degree_id' => $student->degree_id,
            'email' => $student->email,
            'ip' => $request->ip(),
        ]);

        if ($request->expectsJson()) {
            $student->load('degree');

            return response()->json([
                'message' => $student->user_account_id
                    ? 'Student successfully added. The student can now log in from the main login page.'
                    : 'Student successfully added.',
                'student' => $this->studentPayload($student),
            ], 201);
        }

        return redirect()->route('students.show', $student)
            ->with('success', $student->user_account_id
                ? 'Student successfully added. The student can now log in from the main login page.'
                : 'Student successfully added.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Student $student): View|JsonResponse
    {
        $student->load('degree');

        if ($request->expectsJson()) {
            return response()->json([
                'student' => $this->studentPayload($student),
            ]);
        }

        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student): View
    {
        $degrees = Degree::query()
            ->orderBy('title')
            ->get();

        return view('students.edit', compact('student', 'degrees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentRequest $request, Student $student): RedirectResponse|JsonResponse
    {
        $student->update($request->validated());

        if (app()->environment('production')) {
            Log::info('student is updated');
        }

        if ($request->expectsJson()) {
            $student->load('degree');

            return response()->json([
                'message' => 'Student successfully updated.',
                'student' => $this->studentPayload($student),
            ]);
        }

        return redirect()->route('students.show', $student)
            ->with('success', 'Student successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Student $student): RedirectResponse|JsonResponse
    {
        $student->delete();

        if (app()->environment('production')) {
            Log::warning('student is deleted');
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Student successfully deleted.',
            ]);
        }

        return redirect()->route('students.index')
            ->with('success', 'Student successfully deleted.');
    }

    /**
     * Shape student records for AJAX table rendering.
     *
     * @return array<string, mixed>
     */
    private function studentPayload(Student $student): array
    {
        return [
            'id' => $student->id,
            'first_name' => $student->first_name,
            'middle_name' => $student->middle_name,
            'last_name' => $student->last_name,
            'full_name' => $student->full_name,
            'address' => $student->address,
            'contact' => $student->contact,
            'email' => $student->email,
            'degree_id' => $student->degree_id,
            'degree_title' => $student->degree->title,
            'show_url' => route('students.show', $student),
            'update_url' => route('students.update', $student),
            'delete_url' => route('students.destroy', $student),
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
