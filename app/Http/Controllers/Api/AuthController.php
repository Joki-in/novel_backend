<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;



class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'alamat' => 'required|string|max:255',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'alamat' => $request->alamat,
                'password' => Hash::make($request->password),
            ]);

            return response()->json(['status' => 'success', 'message' => 'User registered successfully',], 201);
        } catch (ValidationException $e) {
            return response()->json(['status' => 'error', 'message' => 'Validation error', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to register user', 'error' => $e->getMessage()], 500);
        }
    }
    public function login(Request $request)
    {
        // Validation rules for the request
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
            'fcm_token' => 'required',
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 400);
        }

        try {
            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $token = $user->createToken('AuthToken')->plainTextToken;

                // Save the token to the remember_token column
                $user->update(['remember_token' => $token]);

                // Update FCM token
                $fcmToken = $request->input('fcm_token');
                $user->update(['fcm_token' => $fcmToken]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Login successful',
                    'token' => $token,
                    'user_id' => $user->id
                ], 200);
            } else {
                throw new AuthenticationException('Kombinasi email dan password salah');
            }
        } catch (AuthenticationException $e) {
            $message = $e->getMessage();

            // Determine the type of error (email or password)
            if (strpos($message, 'password') !== false) {
                return response()->json(['status' => 'error', 'message' => 'Invalid password'], 401);
            } elseif (strpos($message, 'email') !== false) {
                return response()->json(['status' => 'error', 'message' => 'Invalid email address'], 401);
            } else {
                return response()->json(['status' => 'error', 'message' => $message], 401);
            }
        }
    }
    public function logout(Request $request)
    {
        try {
            $bearerToken = $request->bearerToken(); // Mengambil token dari header Authorization

            if (!$bearerToken) {
                return response()->json(['status' => 'error', 'message' => 'Bearer token not provided'], 400);
            }

            $user = User::where('remember_token', $bearerToken)->first();

            if (!$user) {
                return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
            }

            // Menghapus remember_token dari pengguna
            $user->update(['remember_token' => null]);
            $user->update(['fcm_token' => null]);

            return response()->json(['status' => 'success', 'message' => 'Logout successful'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to logout'], 500);
        }
    }
}
