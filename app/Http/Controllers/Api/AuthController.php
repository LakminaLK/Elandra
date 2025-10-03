<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        // Create token with scopes based on user role
        $abilities = $this->getUserAbilities($user);
        $token = $user->createToken('api-token', $abilities)->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'User registered successfully',
            'data' => [
                'user' => $user,
                'token' => $token
            ]
        ], 201);
    }

    /**
     * Login user and create token
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials'
            ], 401);
        }

        // Update last login
        $user->update(['last_login_at' => now()]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'data' => [
                'user' => $user,
                'token' => $token
            ]
        ]);
    }

    /**
     * Logout user (revoke token)
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logged out successfully'
        ]);
    }

    /**
     * Get authenticated user
     */
    public function user(Request $request): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => [
                'user' => $request->user()
            ]
        ]);
    }

    /**
     * Refresh token
     */
    public function refresh(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Token refreshed successfully',
            'data' => [
                'token' => $token
            ]
        ]);
    }

    /**
     * Get user's API tokens
     */
    public function tokens(Request $request): JsonResponse
    {
        $tokens = $request->user()->tokens()->get(['id', 'name', 'created_at', 'last_used_at']);

        return response()->json([
            'status' => 'success',
            'data' => [
                'tokens' => $tokens
            ]
        ]);
    }

    /**
     * Revoke specific token
     */
    public function revokeToken(Request $request, $tokenId): JsonResponse
    {
        $token = $request->user()->tokens()->where('id', $tokenId)->first();

        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token not found'
            ], 404);
        }

        $token->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Token revoked successfully'
        ]);
    }

    /**
     * Revoke all tokens
     */
    public function revokeAllTokens(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'All tokens revoked successfully'
        ]);
    }
    
    /**
     * Get user abilities based on role for token scopes
     */
    private function getUserAbilities(User $user): array
    {
        $baseAbilities = [
            'user:read',
            'products:read',
            'cart:read',
            'cart:write',
            'orders:read',
            'orders:write'
        ];
        
        if ($user->role === 'admin') {
            return array_merge($baseAbilities, [
                'admin:read',
                'admin:write',
                'products:write',
                'products:delete',
                'orders:manage',
                'users:read',
                'users:write',
                'categories:write',
                'analytics:read'
            ]);
        }
        
        return $baseAbilities;
    }
    
    /**
     * Create token with specific abilities
     */
    public function createTokenWithAbilities(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'abilities' => 'required|array',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $user = $request->user();
        $userAbilities = $this->getUserAbilities($user);
        
        // Filter requested abilities to only include those the user has
        $allowedAbilities = array_intersect($request->abilities, $userAbilities);
        
        $token = $user->createToken($request->name, $allowedAbilities);
        
        return response()->json([
            'status' => 'success',
            'data' => [
                'token' => $token->plainTextToken,
                'abilities' => $allowedAbilities
            ]
        ]);
    }
}
