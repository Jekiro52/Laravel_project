<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDegreeRequest;
use App\Http\Requests\UpdateDegreeRequest;
use App\Models\Degree;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class DegreeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|JsonResponse
    {
        $degrees = Degree::query()
            ->withCount('students')
            ->orderBy('title')
            ->paginate(10);

        if ($request->expectsJson()) {
            return response()->json([
                'degrees' => $degrees->getCollection()
                    ->map(fn (Degree $degree) => $this->degreePayload($degree))
                    ->values(),
                'pagination' => $this->paginationPayload($degrees),
            ]);
        }

        return view('degrees.index', compact('degrees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('degrees.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDegreeRequest $request): RedirectResponse|JsonResponse
    {
        $degree = Degree::query()->create($request->validated());
        $degree->loadCount('students');

        Log::info('degree.created', [
            'degree_id' => $degree->id,
            'title' => $degree->title,
            'ip' => $request->ip(),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Degree successfully added.',
                'degree' => $this->degreePayload($degree),
            ], 201);
        }

        return redirect()->route('degrees.show', $degree)
            ->with('success', 'Degree successfully added.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Degree $degree): View|JsonResponse
    {
        $degree->load([
            'students' => fn ($query) => $query
                ->orderBy('last_name')
                ->orderBy('first_name'),
        ]);
        $degree->loadCount('students');

        if ($request->expectsJson()) {
            return response()->json([
                'degree' => $this->degreePayload($degree),
            ]);
        }

        return view('degrees.show', compact('degree'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Degree $degree): View
    {
        return view('degrees.edit', compact('degree'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDegreeRequest $request, Degree $degree): RedirectResponse|JsonResponse
    {
        $previousTitle = $degree->title;

        $degree->update($request->validated());
        $degree->loadCount('students');

        Log::info('degree.updated', [
            'degree_id' => $degree->id,
            'previous_title' => $previousTitle,
            'current_title' => $degree->title,
            'ip' => $request->ip(),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Degree successfully updated.',
                'degree' => $this->degreePayload($degree),
            ]);
        }

        return redirect()->route('degrees.show', $degree)
            ->with('success', 'Degree successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Degree $degree): RedirectResponse|JsonResponse
    {
        $studentCount = $degree->students()->count();

        if ($studentCount > 0) {
            Log::warning('degree.delete_blocked', [
                'degree_id' => $degree->id,
                'title' => $degree->title,
                'students_count' => $studentCount,
                'ip' => $request->ip(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Cannot delete a degree that still has enrolled students.',
                ], 409);
            }

            return redirect()->route('degrees.index')
                ->with('error', 'Cannot delete a degree that still has enrolled students.');
        }

        $logContext = [
            'degree_id' => $degree->id,
            'title' => $degree->title,
            'ip' => $request->ip(),
        ];

        $degree->delete();

        Log::warning('degree.deleted', $logContext);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Degree successfully deleted.',
            ]);
        }

        return redirect()->route('degrees.index')
            ->with('success', 'Degree successfully deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function degreePayload(Degree $degree): array
    {
        $students = $degree->relationLoaded('students')
            ? $degree->students->map(fn ($student) => [
                'id' => $student->id,
                'full_name' => $student->full_name,
                'email' => $student->email,
            ])->values()
            : collect();

        return [
            'id' => $degree->id,
            'title' => $degree->title,
            'students_count' => $degree->students_count ?? $degree->students()->count(),
            'students' => $students,
            'show_url' => route('degrees.show', $degree),
            'update_url' => route('degrees.update', $degree),
            'delete_url' => route('degrees.destroy', $degree),
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
