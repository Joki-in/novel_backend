<?php

use App\Http\Controllers\Api\NovelPageTampilController;
use App\Http\Controllers\Api\SearchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BukuController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ForgotPasswordController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//Auth routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [ForgotPasswordController::class, 'forgotPassword']);
Route::post('/check-otp', [ForgotPasswordController::class, 'checkOTP']);
Route::post('/reset-password', [ForgotPasswordController::class, 'changePassword']);
Route::post('/logout', [AuthController::class, 'logout']);

//Profile routes
Route::post('/profile', [ProfileController::class, 'profile']);
Route::put('/update-profile', [ProfileController::class, 'updateProfile']);
Route::post('/update-fotoprofile', [ProfileController::class, 'updateProfilePhoto']);

//Dashboard routes
Route::get('/top-like', [BukuController::class, 'TopLikedBooks']);
Route::get('/top-view', [BukuController::class, 'topView']);

//search controller
Route::post('/search2', [SearchController::class, 'search2']);

//NovelPageController routes
Route::post('/novelpagebuku', [NovelPageTampilController::class, 'BukuDanPenulis']);
Route::post('/novelpageisi', [NovelPageTampilController::class, 'IsiBerdasarkanBuku']);
Route::post('/novelpagekomentar', [NovelPageTampilController::class, 'getKomentarByBukuId']);
