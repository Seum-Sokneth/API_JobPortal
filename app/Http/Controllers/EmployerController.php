<?php

namespace App\Http\Controllers;

use App\Models\Employer_Profile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmployerController extends Controller
{
    public function index()
    {
        return response()->json(Employer_Profile::get());
    }

    public function show(int $id): JsonResponse
    {
        $employer = Employer_Profile::find($id);
        if (! $employer) {
            return response()->json([
                'message' => 'Employer Not Found',
            ], 404);
        }

        return response()->json([
            'message' => 'Employer Found Successfully',
            'data' => $employer,
        ], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'nullable|integer|exists:users,id',
            'company_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:employer_profile,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $employer = Employer_Profile::create($validated);

        return response()->json([
            'message' => 'Employer Created Successfully',
            'data' => $employer,
        ], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $employer = Employer_Profile::find($id);
        if (! $employer) {
            return response()->json([
                'message' => 'Employer Not Found',
            ], 404);
        }

        $validated = $request->validate([
            'user_id' => 'nullable|integer|exists:users,id',
            'company_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:employer_profile,email,'.$id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $employer->update($validated);

        return response()->json([
            'message' => 'Employer Updated Successfully',
            'data' => $employer,
        ], 200);
    }

    public function destroy(int $id): JsonResponse
    {
        $employer = Employer_Profile::find($id);
        if (! $employer) {
            return response()->json([
                'message' => 'Employer Not Found',
            ], 404);
        }

        $employer->delete();

        return response()->json([
            'message' => 'Employer Deleted Successfully',
        ], 200);
    }
}
