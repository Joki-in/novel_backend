<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Mail\OtpMail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Exceptions\ThrottleRequestsException;

class ForgotPasswordController extends Controller
{
    public function forgotPassword(Request $request)
    {

        // Validasi input
        $request->validate([
            'email' => 'required|email',
        ]);

        // Cek apakah email ada di database
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'Email not found'], 404);
        }

        // Generate OTP
        $otp = random_int(1000, 9999);

        // Simpan OTP di database
        $user->otp = $otp;
        $user->save();

        // Kirim email OTP
        Mail::raw('Your OTP is: ' . $otp, function ($message) use ($user) {
            $message->to($user->email)->subject('Your OTP');
        });

        return response()->json(['message' => 'OTP generated and sent successfully', 'email' => $user->email]);
    }
    public function checkOTP(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'email' => 'required|email',
                'otp' => 'required|digits:4',
            ]);

            // Cek apakah email ada di database
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json(['message' => 'Email not found'], 404);
            }

            // Check OTP
            if ($user->otp == $request->otp) {
                // Reset OTP setelah digunakan
                $user->otp = null;
                $user->status = 1;
                $user->save();

                return response()->json(['status' => 'success'], 200);
            } else {
                // OTP tidak sesuai
                return response()->json(['message' => 'Invalid OTP'], 400);
            }
        } catch (QueryException $e) {
            // Jika terjadi kesalahan database
            return response()->json(['message' => 'Database error: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            // Jika terjadi kesalahan lainnya
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
    public function changePassword(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|min:6',
            ]);

            // Cek apakah email ada di database
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json(['message' => 'Email not found'], 404);
            }

            // Ganti password
            $user->password = Hash::make($request->password);
            $user->save();

            return response()->json(['message' => 'Password changed successfully']);
        } catch (\Exception $e) {
            // Jika terjadi kesalahan
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

}
