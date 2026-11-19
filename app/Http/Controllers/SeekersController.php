<?php

namespace App\Http\Controllers;

use App\Models\SeekersProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SeekersController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'q' => ['sometimes', 'string', 'max:255'],
        ]);

        $seekers = SeekersProfile::query()
            ->when(isset($validated['q']), function ($query) use ($validated): void {
                $query->where('fullname', 'like', '%'.$validated['q'].'%')
                    ->orWhere('email', 'like', '%'.$validated['q'].'%');
            })
            ->latest()
            ->get();

        return response()->json($seekers);
    }

    public function index()
    {
        return response()->json(SeekersProfile::all(), 200);
    }

    public function show($id)
    {

        $seeker = SeekersProfile::find($id);
        if (! $seeker) {
            return response()->json([
                'message' => 'Seeker not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Seeker found successfully',
            'data' => $seeker,
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|integer|exists:users,id',
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|unique:seekers_profiles,email',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string|max:20',
        ]);

        $seeker = SeekersProfile::create($validated);

        return response()->json([
            'message' => 'Seeker created successfully',
            'data' => $seeker,
        ], 201);
    }

    public function update(Request $request, $id)
    {

        $seeker = SeekersProfile::find($id);

        if (! $seeker) {
            return response()->json([
                'message' => 'Seeker not found',
            ], 404);
        }

        $validated = $request->validate([
            'fullname' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:seekers_profiles,email,'.$id,
            'password' => 'sometimes|required|string|min:6',
            'phone' => 'nullable|string|max:20',
        ]);

        $seeker->update($validated);

        return response()->json([
            'message' => 'Seeker updated successfully',
            'data' => $seeker,
        ], 200);
    }

    public function destroy($id)
    {
        $seeker = SeekersProfile::find($id);

        if (! $seeker) {
            return response()->json([
                'message' => 'Seeker not found',
            ], 404);
        }

        $seeker->delete();

        return response()->json([
            'message' => 'Seeker deleted successfully',
        ], 200);
    }
}
