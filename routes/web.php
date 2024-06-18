<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [AuthController::class, 'indexLogin']);
Route::post('/masuk', [AuthController::class, 'storeLogin'])->name('login');

Route::get('/register', [AuthController::class, 'registerShow']);
Route::post('/register', [AuthController::class, 'storeRegister'])->name('register');