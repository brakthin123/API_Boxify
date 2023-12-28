<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{

    // function register user

    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|min:2|max:100',
                'email' => 'required|string|email|max:100|unique:users',
                'password' => 'required|string|min:6',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ], 400);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Generate JWT token
            $token = JWTAuth::fromUser($user);

            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'data' => ['token' => $token, 'user' => $user],
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }


    /**
     * Login The User
     * @param Request $request
     * @return User
     */


    public function login(Request $request)
    {

        // data validation
        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        // JWTAuth
        $token = JWTAuth::attempt([
            "email" => $request->email,
            "password" => $request->password
        ]);

        if (!empty($token)) {
            $user = Auth::user();
            return response()->json([

                "status" => true,
                "message" => "User logged in succcessfully",
                'data' => ["token" => $token, "user" => $user],


            ]);
        } else {
            return response()->json([
                "status" => false,
                "message" => "Invalid details"
            ]);
        }
    }


    // User Profile
    public function profile()
    {
        $user = User::first();  // Replace with your actual logic

        if ($user) {
            return response()->json(['message' => 'User profile retrieved successfully', 'user' => $user]);
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }


    // To generate refresh token value
    public function refreshToken()
    {

        $newToken = Auth::refresh();

        return response()->json([
            "status" => true,
            "message" => "New access token",
            "token" => $newToken
        ]);
    }

    public function logout(Request $request)
    {
        // Check if the user is authenticated
        if (Auth::check()) {
            // Invalidate the JWT token
            $token = JWTAuth::getToken();

            if ($token) {
                try {
                    JWTAuth::invalidate($token);
                } catch (TokenInvalidException $e) {
                    // Handle token invalidation exception if needed
                    return response()->json(['error' => 'Failed to invalidate token'], 500);
                }
            }

            // Logout the user
            Auth::logout();

            return response()->json(['message' => 'Successfully logged out']);
        } else {
            return response()->json(['message' => 'User not authenticated'], 401);
        }
    }
    
}
