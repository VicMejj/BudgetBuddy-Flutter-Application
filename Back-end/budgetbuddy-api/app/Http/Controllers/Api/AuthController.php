<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
public function register(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name'     => 'required|string|max:100',
        'email'    => 'required|email|unique:users',
        'password' => 'required|string|min:6|confirmed',
        'admin_key' => 'sometimes|string',
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    // Default role
    $role = 'user';
    
    // Check for admin key
    if ($request->has('admin_key')) {
        $expectedKey = env('ADMIN_REGISTRATION_KEY', 'default-admin-secret-123');
        
        // Debug: Log what we're comparing
        \Log::info('Admin key check:', [
            'provided' => $request->admin_key,
            'expected' => $expectedKey,
            'matches' => $request->admin_key === $expectedKey
        ]);
        
        if ($request->admin_key === $expectedKey) {
            $role = 'admin';
        }
    }

    $user = User::create([
        'name'     => $request->name,
        'email'    => $request->email,
        'password' => Hash::make($request->password),
        'role'     => $role,
    ]);

    $token = JWTAuth::fromUser($user);

    return response()->json([
        'message' => 'User registered successfully',
        'user'    => $user,
        'token'   => $token,
    ], 201);
}

    /**
     * Login user and return a token
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Explicitly use jwt attempt
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get authenticated user
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Logout user (invalidate token)
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh JWT token
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Helper: format token response
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->factory()->getTTL() * 60,
            'user'         => auth()->user()
        ]);
    }
}