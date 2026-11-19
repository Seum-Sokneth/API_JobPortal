<?php

namespace App\Http\Controllers;

use App\Models\JobPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JobPostController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'q' => ['sometimes', 'string', 'max:255'],
            'department_id' => ['sometimes', 'integer', 'exists:job_departement,id'],
        ]);

        $jobPosts = JobPost::query()
            ->with(['company', 'department'])
            ->where('status', 'published')
            ->when(isset($validated['q']), function ($query) use ($validated): void {
                $query->where(function ($query) use ($validated): void {
                    $query->where('about_role', 'like', '%'.$validated['q'].'%')
                        ->orWhereHas('company', fn ($companyQuery) => $companyQuery->where('name', 'like', '%'.$validated['q'].'%'));
                });
            })
            ->when(isset($validated['department_id']), fn ($query) => $query->where('department_id', $validated['department_id']))
            ->latest()
            ->get();

        return response()->json($jobPosts);
    }

    public function index(): JsonResponse
    {
        return response()->json(
            JobPost::with(['company', 'department'])
                ->where('status', 'published')
                ->get());
    }

    public function show(int $id): JsonResponse
    {
        $jobPost = JobPost::with(['company', 'department'])
            ->where('status', 'published')
            ->find($id);

        if (! $jobPost) {
            return response()->json([
                'message' => 'Job Post Not Found',
            ], 404);
        }

        return response()->json([
            'message' => 'Job Post Found Successfully',
            'data' => $jobPost,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'company_id' => 'required|integer|exists:companies,id',
            'department_id' => 'required|integer|exists:job_departement,id',
            'job_name' => 'nullable|string|max:255',
            'post_date' => 'nullable|date',
            'deadline' => 'nullable|date|after_or_equal:post_date',
            'employment_type' => 'nullable|string|max:255',
            'experience_level' => 'nullable|string|max:255',
            'minimum_salary' => 'nullable|numeric|min:0',
            'maximum_salary' => 'nullable|numeric|min:0|gte:minimum_salary',
            'about_role' => 'nullable|string|max:10000',
            'responsibilities' => 'nullable|string|max:10000',
            'required_qualifications' => 'nullable|string|max:10000',
            'tech_stack' => 'nullable|string|max:255',
            'currency' => 'nullable|string|max:8',
            'benefits' => 'nullable|string|max:10000',
        ]);

        $validated['status'] = 'draft';
        $validated['published_at'] = null;

        $jobPost = JobPost::create($validated);

        return response()->json([
            'message' => 'Job Post Created Successfully',
            'data' => $jobPost,
        ], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $jobPost = JobPost::find($id);

        if (! $jobPost) {
            return response()->json(['message' => 'Job Post Not Found'], 404);
        }

        $validated = $request->validate([
            'job_name' => 'sometimes|nullable|string|max:255',
            'company_id' => 'sometimes|required|integer|exists:companies,id',
            'department_id' => 'sometimes|required|integer|exists:job_departement,id',
            'post_date' => 'sometimes|nullable|date',
            'deadline' => 'sometimes|nullable|date|after_or_equal:post_date',
            'employment_type' => 'sometimes|nullable|string|max:255',
            'experience_level' => 'sometimes|nullable|string|max:255|in:entry-level,mid-level,senior-level,manager,executive',
            'minimum_salary' => 'sometimes|nullable|numeric|min:0',
            'maximum_salary' => 'sometimes|nullable|numeric|min:0|gte:minimum_salary',
            'about_role' => 'sometimes|nullable|string|max:10000',
            'responsibilities' => 'sometimes|nullable|string|max:10000',
            'required_qualifications' => 'sometimes|nullable|string|max:10000',
            'tech_stack' => 'sometimes|nullable|string|max:255',
            'currency' => 'sometimes|nullable|string|max:8',
            'benefits' => 'sometimes|nullable|string|max:10000',
        ]);

        $jobPost->update($validated);

        return response()->json([
            'message' => 'Job Post Updated Successfully',
            'data' => $jobPost->fresh(['company', 'department']),
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $jobPost = JobPost::find($id);

        if (! $jobPost) {
            return response()->json(['message' => 'Job Post Not Found'], 404);
        }

        $jobPost->delete();

        return response()->json(['message' => 'Job Post Deleted Successfully']);
    }

    public function pendingForAdmin(Request $request): JsonResponse
    {
        abort_unless($request->user()?->role === 'admin', 403);
        $jobPosts = JobPost::with(['company', 'department'])
            ->where('status', 'draft')
            ->latest()
            ->get();

        return response()->json([
            'message' => 'Pending job posts retrieved successfully',
            'data' => $jobPosts,
        ]);
    }

    public function approve(Request $request, JobPost $jobPost): JsonResponse
    {

        abort_unless($request->user()?->role === 'admin', 403);
        if ($jobPost->status === 'closed') {
            return response()->json([
                'message' => 'A closed job post cannot be approved.',
            ], 422);
        }

        if ($jobPost->status !== 'published') {
            $jobPost->update([
                'status' => 'published',
                'published_at' => now(),
            ]);
        }

        return response()->json([
            'message' => 'Job post approved and published successfully',
            'data' => $jobPost->fresh(['company', 'department']),
        ]);
    }
}
