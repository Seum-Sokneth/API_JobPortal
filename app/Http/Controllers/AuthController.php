<?php

namespace App\Http\Controllers;

use App\Models\Employer_Profile;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::query()->where('email', $validated['email'])->first();

        if ($user === null || ! Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials.',
            ], 401);
        }
        

        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'status' => 'success',
            'message' => 'User logged in successfully.',
            'data' => $user,
            'token' => $token,
        ]);
    }

    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'role' => ['required', 'string', 'in:employer,seeker'],
            'company_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:6'],
            'confirm_password' => ['required', 'same:password'],
        ]);

        [$user, $employerProfile, $seekerProfile] = DB::transaction(function () use ($validated): array {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
                'password' => $validated['password'],
            ]);

            $employerProfile = null;
            $seekerProfile = null;

            if ($user->role === 'employer') {
                $employerProfile = Employer_Profile::create([
                    'user_id' => $user->id,
                    'company_name' => $validated['company_name'] ?? $user->name,
                    'email' => $user->email,
                ]);
            } elseif ($user->role === 'seeker') {
                $seekerProfile = $user->seekerProfile()->create([
                    'fullname' => $user->name,
                    'email' => $user->email,
                    'password' => $validated['password'],
                    'phone' => $validated['phone'] ?? '',
                ]);
            }

            return [$user, $employerProfile, $seekerProfile];
        });

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'User registered successfully',
            'data' => $user,
            'employer_profile' => $employerProfile,
            'seeker_profile' => $seekerProfile,
            'token' => $token,
        ], 201);
    }

    public function logout(Request $request): JsonResponse
    {

        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logged out successfully.',
        ], 200);
    }
}
