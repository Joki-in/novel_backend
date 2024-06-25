<?php

<<<<<<< Updated upstream
=======
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
>>>>>>> Stashed changes
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

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

<<<<<<< Updated upstream
Route::get('/', function () {
    return view('welcome');
});
=======
// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [AuthController::class, 'indexLogin']);
Route::post('/masuk', [AuthController::class, 'storeLogin'])->name('login');

Route::get('/register', [AuthController::class, 'registerShow']);
Route::post('/register', [AuthController::class, 'storeRegister'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

//admin
// Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

Route::middleware(['islogin'])->group(function () {
    Route::get('/dashboardAdmin', [DashboardController::class, 'index'])->name('dashboard.index');

});
>>>>>>> Stashed changes
