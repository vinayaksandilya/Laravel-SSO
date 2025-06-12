<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\Client;
use Laravel\Passport\Token;

class SSOAuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('SSO Token')->accessToken;

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully',
            'user' => $user,
            'token' => $token
        ], 201);
    }

    /**
     * Login user and create token
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $token = $user->createToken('SSO Token')->accessToken;

            return response()->json([
                'success' => true,
                'message' => 'User logged in successfully',
                'user' => $user,
                'token' => $token
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials'
        ], 401);
    }

    /**
     * Get authenticated user details
     */
    public function user(Request $request)
    {
        return response()->json([
            'success' => true,
            'user' => $request->user()
        ]);
    }

    /**
     * Logout user (revoke token)
     */
    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();

        return response()->json([
            'success' => true,
            'message' => 'User logged out successfully'
        ]);
    }

    /**
     * Verify token validity
     */
    public function verifyToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Token is required',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Find the token
            $token = Token::where('id', $request->token)->first();
            
            if (!$token || $token->revoked) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token is invalid or revoked'
                ], 401);
            }

            // Check if token is expired
            if ($token->expires_at && $token->expires_at->isPast()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token has expired'
                ], 401);
            }

            // Get the user
            $user = User::find($token->user_id);

            return response()->json([
                'success' => true,
                'message' => 'Token is valid',
                'user' => $user
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid token format'
            ], 401);
        }
    }

    /**
     * Get OAuth clients for the authenticated user
     */
    public function getClients(Request $request)
    {
        $clients = $request->user()->clients;

        return response()->json([
            'success' => true,
            'clients' => $clients
        ]);
    }

    /**
     * Create a new OAuth client
     */
    public function createClient(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'redirect' => 'required|url',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $client = $request->user()->clients()->create([
            'name' => $request->name,
            'secret' => \Str::random(40),
            'redirect' => $request->redirect,
            'personal_access_client' => false,
            'password_client' => false,
            'revoked' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Client created successfully',
            'client' => $client
        ], 201);
    }
}
