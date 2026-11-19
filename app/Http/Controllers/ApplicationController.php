<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\JobPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index()
    {
        return response()->json(Application::all(), 200);
    }

    public function show($id)
    {
        $application = Application::find($id);
        if (! $application) {
            return response()->json([
                'message' => 'Application not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Application found',
            'data' => $application,
        ], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'seeker_id' => 'required|integer|exists:seekers_profiles,id',
            'job_post_id' => 'required|integer|exists:job_post,id',
            'resume_id' => 'required|integer|exists:resume,id',
            'cover_letter' => 'nullable|string|max:1000',
            'file_path' => 'nullable|file|mimes:pdf|max:10240',
            'status' => 'sometimes|string|max:255|in:pending,accepted,rejected',
            'applied_at' => 'nullable|date',
        ]);

        $isJobPostPublished = JobPost::query()
            ->whereKey($validated['job_post_id'])
            ->where('status', 'published')
            ->exists();

        if (! $isJobPostPublished) {
            return response()->json([
                'message' => 'Applications can only be submitted to approved and published job posts.',
            ], 422);
        }

        $filePath = null;
        if ($request->hasFile('file_path')) {
            $filePath = $request->file('file_path')->store('applications', 'local');
            if ($filePath === false) {
                return response()->json([
                    'message' => 'Application file could not be stored',
                ], 500);
            }

            $validated['file_path'] = $filePath;
        }
        $application = Application::create($validated);

        return response()->json([
            'message' => 'Application created successfully',
            'data' => $application,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $application = Application::find($id);

        if (! $application) {
            return response()->json([
                'message' => 'Application not found',
            ], 404);
        }

        $validated = $request->validate([
            'seeker_id' => 'sometimes|required|integer|exists:seekers_profiles,id',
            'job_post_id' => 'sometimes|required|integer|exists:job_post,id',
            'resume_id' => 'sometimes|required|integer|exists:resume,id',
            'cover_letter' => 'nullable|string|max:1000',
            'file_path' => 'nullable|file|mimes:pdf|max:10240',
            'status' => 'sometimes|required|string|max:255|in:pending,accepted,rejected',
            'applied_at' => 'nullable|date',
        ]);

        $filePath = null;
        if ($request->hasFile('file_path')) {
            $filePath = $request->file('file_path')->store('applications', 'local');
            if ($filePath === false) {
                return response()->json([
                    'message' => 'Application file could not be stored',
                ], 500);
            }
            $validated['file_path'] = $filePath;
        }

        $application->update($validated);

        return response()->json([
            'message' => 'Application updated successfully',
            'data' => $application,
        ], 200);
    }

    public function destroy($id)
    {
        $application = Application::find($id);
        if (! $application) {
            return response()->json([
                'message' => 'Application not found',
            ], 404);
        }

        $application->delete();

        return response()->json([
            'message' => 'Application deleted successfully',
        ], 200);
    }
}
