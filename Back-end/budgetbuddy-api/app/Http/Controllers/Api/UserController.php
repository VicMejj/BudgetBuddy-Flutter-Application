<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    /**
     * Display a listing of users (Admin only).
     */
    public function index()
    {
        $user = auth()->user();
        if ($user->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $users = User::all();
        return response()->json($users);
    }

    /**
     * Display the authenticated user (token-based).
     */
    public function me()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            return response()->json($user);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token invalid or expired'], 401);
        }
    }

    /**
     * Update the authenticated user profile.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'sometimes|string|max:100',
            'email' => ['sometimes', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'sometimes|string|min:6',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user
        ]);
    }

    /**
     * Delete the authenticated user or another user (Admin only).
     */
    public function destroy(Request $request, $id = null)
    {
        $currentUser = auth()->user();

        // Admin deleting another user
        if ($currentUser->role === 'admin' && $id) {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json(['message' => 'User deleted by admin']);
        }

        // Normal user deleting their own account
        $currentUser->delete();

        // Invalidate JWT token
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
        } catch (JWTException $e) {
            // Token already invalidated or missing
        }

        return response()->json(['message' => 'Account deleted successfully']);
    }

    /**
     * Mute a user (Admin only)
     */
    public function mute($id)
    {
        $currentUser = auth()->user();
        if ($currentUser->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $user = User::findOrFail($id);
        $user->status = 'muted';
        $user->save();

        return response()->json(['message' => 'User muted successfully', 'user' => $user]);
    }

    /**
     * Ban a user (Admin only)
     */
    public function ban($id)
    {
        $currentUser = auth()->user();
        if ($currentUser->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $user = User::findOrFail($id);
        $user->status = 'banned';
        $user->save();

        return response()->json(['message' => 'User banned successfully', 'user' => $user]);
    }

    /**
     * Activate a user (Admin only)
     */
    public function activate($id)
    {
        $currentUser = auth()->user();
        if ($currentUser->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $user = User::findOrFail($id);
        $user->status = 'active';
        $user->save();

        return response()->json(['message' => 'User activated successfully', 'user' => $user]);
    }
}
