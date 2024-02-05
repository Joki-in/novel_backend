<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;

class ProfileController extends Controller
{
    /**
     * Get user profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function profile(Request $request)
    {
        // Get token from request headers
        $token = $request->bearerToken();

        // Get authenticated user based on remember_token
        $user = User::where('remember_token', $token)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user, 200);
    }

    /**
     * Update user profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request)
    {
        // Get token from request headers
        $token = $request->bearerToken();

        // Get authenticated user based on remember_token
        $user = User::where('remember_token', $token)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Validate request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'alamat' => 'required|string|max:255',

        ]);

        try {
            // Update user profile
            $user->update([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'alamat' => $validatedData['alamat'],

            ]);

            return response()->json(['message' => 'Success'], 200);
        } catch (\Exception $e) {
            // Handle any unexpected errors
            return response()->json(['message' => 'An error occurred while updating user profile'], 500);
        }
    }

    public function updateProfilePhoto(Request $request)
    {
        try {
            // Get bearer token from request
            $bearerToken = $request->bearerToken();

            // Find user based on token
            $user = User::where('remember_token', $bearerToken)->first();

            if (!$user) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            // Validate request data
            $validatedData = $request->validate([
                'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // max 2MB
            ]);

            // Check if user already has a photo
            if ($user->foto) {
                // Delete existing photo
                unlink(public_path('fotouser/' . $user->foto));
            }

            // Save new photo
            $foto = $request->file('foto');
            $fotoName = $user->id . '_' . time() . '.' . $foto->getClientOriginalExtension();
            $foto->move(public_path('fotouser'), $fotoName);

            // Update photo path in user record
            $user->foto = $fotoName;
            $user->save();

            return response()->json(['message' => 'Profile foto updated successfully'], 200);
        } catch (\Exception $e) {
            // Handle exceptions and send response
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

}
