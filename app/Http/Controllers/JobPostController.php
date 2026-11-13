<?php

namespace App\Http\Controllers;

use App\Models\JobPost;
use Illuminate\Http\Request;

class JobPostController extends Controller
{
    public function index() {
        return response()->json(
            JobPost::with(['company', 'department'])->get());
    }

    public function show($id){

        $jobPost = JobPost::with(['company', 'department'])->find($id);

        if (!$jobPost) {
            return response()->json([
                'message' => 'Job Post Not Found'
            ], 404);
        }

        return response()->json([
            'message' => 'Job Post Found Successfully',
            'data' => $jobPost,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|integer|exists:companies,id',
            'department_id' => 'required|integer|exists:job_departement,id',
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
            'status' => 'sometimes|required|string|in:draft,published,closed',
            'published_at' => 'nullable|date',
        ]);

        $jobPost = JobPost::create($validated);

        return response()->json([
            'message' => 'Job Post Created Successfully',
            'data' => $jobPost,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $jobPost = JobPost::find($id);

        if (!$jobPost) {
            return response()->json(['message' => 'Job Post Not Found'], 404);
        }

        $validated = $request->validate([
            'company_id' => 'sometimes|required|integer|exists:companies,id',
            'department_id' => 'sometimes|required|integer|exists:job_departement,id',
            'post_date' => 'sometimes|nullable|date',
            'deadline' => 'sometimes|nullable|date|after_or_equal:post_date',
            'employment_type' => 'sometimes|nullable|string|max:255',
            'experience_level' => 'sometimes|nullable|string|max:255',
            'minimum_salary' => 'sometimes|nullable|numeric|min:0',
            'maximum_salary' => 'sometimes|nullable|numeric|min:0|gte:minimum_salary',
            'about_role' => 'sometimes|nullable|string|max:10000',
            'responsibilities' => 'sometimes|nullable|string|max:10000',
            'required_qualifications' => 'sometimes|nullable|string|max:10000',
            'tech_stack' => 'sometimes|nullable|string|max:255',
            'currency' => 'sometimes|nullable|string|max:8',
            'benefits' => 'sometimes|nullable|string|max:10000',
            'status' => 'sometimes|required|string|in:draft,published,closed',
            'published_at' => 'sometimes|nullable|date',
        ]);

        $jobPost->update($validated);

        return response()->json([
            'message' => 'Job Post Updated Successfully',
            'data' => $jobPost->fresh(['company', 'department']),
        ]);
    }

    public function destroy($id)
    {
        $jobPost = JobPost::find($id);

        if (!$jobPost) {
            return response()->json(['message' => 'Job Post Not Found'], 404);
        }

        $jobPost->delete();

        return response()->json(['message' => 'Job Post Deleted Successfully']);
    }
}
