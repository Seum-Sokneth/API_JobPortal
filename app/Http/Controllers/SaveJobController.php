<?php

namespace App\Http\Controllers;

use App\Models\SaveJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SaveJobController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(SaveJob::query()->latest()->get());
    }

    public function store(Request $request): JsonResponse
    {

        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'job_id' => ['required', 'integer', 'exists:job_post,id'],
        ]);

        $saveJob = SaveJob::create($validated);

        return response()->json([
            'message' => 'Job saved successfully.',
            'data' => $saveJob,
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $saveJob = SaveJob::query()->find($id);
        if ($saveJob === null) {
            return response()->json([
                'message' => 'Saved job not found.',
            ], 404);
        }

        return response()->json($saveJob);
    }

    public function update(Request $request, int $id): JsonResponse
    {

        $saveJob = SaveJob::query()->find($id);
        if ($saveJob === null) {
            return response()->json(['message' => 'Saved job not found.'], 404);
        }
        $validated = $request->validate([
            'user_id' => ['sometimes', 'required', 'integer', 'exists:users,id'],
            'job_id' => ['sometimes', 'required', 'integer', 'exists:job_post,id'],
        ]);

        $saveJob->update($validated);

        return response()->json([
            'message' => 'Saved job updated successfully.',
            'data' => $saveJob->refresh(),
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $saveJob = SaveJob::query()->find($id);

        if ($saveJob === null) {
            return response()->json(['message' => 'Saved job not found.'], 404);
        }

        $saveJob->delete();

        return response()->json(null, 204);
    }
}
