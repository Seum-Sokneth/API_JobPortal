<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\JobPost;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        abort_unless($request->user()?->role === 'admin', 403);

        $validated = $request->validate([
            'role' => ['sometimes', 'nullable', 'string', 'in:admin,employer,seeker'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ]);

        $usersQuery = User::query()
            ->select(['id', 'name', 'email', 'role', 'created_at']);

        if (isset($validated['role'])) {
            $usersQuery->where('role', $validated['role']);
        }

        $users = $usersQuery
            ->latest()
            ->paginate($validated['per_page'] ?? 15)
            ->withQueryString();

        return response()->json([
            'status' => 'success',
            'data' => [
                'totals' => [
                    'users' => User::query()->count(),
                    'companies' => Company::query()->count(),
                    'job_posts' => JobPost::query()->count(),
                ],
                'users' => $users,
            ],
        ]);
    }
}
