<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::all(), 200);

    }

    public function show($id)
    {
        $user = User::find($id);
        if (! $user) {
            return response()->json([
                'message' => 'User Not Found',
            ], 404);
        }

        return response()->json([
            'message' => 'User Found Successfully',
            'data' => $user,
        ], 200);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if (! $user) {
            return response()->json([
                'message' => 'User Not Found',
            ], 404);
        }

        $user->delete();

        return response()->json([
            'message' => 'User Deleted Successfully',
        ], 200);
    }
}
