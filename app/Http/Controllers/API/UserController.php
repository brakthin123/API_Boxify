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
                ], 422);
            }
    
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
    
            return response()->json([

                'status' => true,
                'message' => 'User Created Successfully',
                'data' => ['token' => $user->createToken("API TOKEN")->accessToken, "user" => $user],
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
        try {
            // Attempt to authenticate the user using the provided JWT token
            $user = JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            // If authentication fails, return an 'Unauthorized' response with a 401 status code
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // If authentication is successful, return the user data in a JSON response
        return response()->json($user);
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
