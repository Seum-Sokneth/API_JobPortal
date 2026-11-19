<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ResumeController extends Controller
{
    public function index()
    {
        return response()->json(Resume::all(), 200);
    }

    public function show($id)
    {

        $resume = Resume::find($id);
        if (! $resume) {
            return response()->json([
                'message' => 'Resume not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Resume found',
            'data' => $resume,
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'seeker_id' => 'required|integer|exists:seekers_profiles,id',
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|unique:resume,email',
            'phone' => 'nullable|string|max:20',
            'file_path' => 'required|file|mimes:pdf|max:10240',
        ]);

        $filePath = $request->file('file_path')->store('resumes', 'local');
        if ($filePath === false) {
            return response()->json([
                'message' => 'Resume file could not be stored',
            ], 500);
        }

        $validated['file_path'] = $filePath;
        $resume = Resume::create($validated);

        return response()->json([
            'message' => 'Resume created successfully',
            'data' => $resume,
        ], 201);
    }

    public function update(Request $request, $id)
    {

        $resume = Resume::find($id);

        if (! $resume) {
            return response()->json([
                'message' => 'Resume not found',
            ], 404);
        }

        $validated = $request->validate([
            'seeker_id' => 'sometimes|required|integer|exists:seekers_profiles,id',
            'fullname' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:resume,email,'.$id,
            'phone' => 'nullable|string|max:20',
            'file_path' => 'sometimes|required|file|mimes:pdf|max:10240',
        ]);

        $previousFilePath = null;

        if ($request->hasFile('file_path')) {
            $filePath = $request->file('file_path')->store('resumes', 'local');

            if ($filePath === false) {
                return response()->json([
                    'message' => 'Resume file could not be stored',
                ], 500);
            }

            $previousFilePath = $resume->file_path;
            $validated['file_path'] = $filePath;
        }

        $resume->update($validated);

        if ($previousFilePath !== null) {
            Storage::disk('local')->delete($previousFilePath);
        }

        return response()->json([
            'message' => 'Resume updated successfully',
            'data' => $resume,
        ], 200);
    }

    public function destroy($id)
    {

        $resume = Resume::find($id);
        if (! $resume) {
            return response()->json([
                'message' => 'Resume not found',
            ], 404);
        }

        $resume->delete();
        Storage::disk('local')->delete($resume->file_path);

        return response()->json([
            'message' => 'Resume deleted successfully',
        ], 200);
    }
}
