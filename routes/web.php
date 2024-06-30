<?php
use App\Http\Controllers\TambahIsiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TerimaIsiController;
use App\Http\Controllers\TambahBukuController;
use App\Http\Controllers\TerimaBukuController;
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

Route::get('/', [AuthController::class, 'indexLogin']);
Route::post('/masuk', [AuthController::class, 'storeLogin'])->name('login');

Route::get('/register', [AuthController::class, 'registerShow']);
Route::post('/register', [AuthController::class, 'storeRegister'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

//admin
// Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

Route::middleware(['islogin'])->group(function () {
    //Admin
    //Dashboard
    Route::get('/dashboardAdmin', [DashboardController::class, 'index'])->name('dashboard.index');

    //terima buku
    Route::get('/terima-buku-admin', [TerimaBukuController::class, 'index'])->name('terima-buku.index');
    Route::put('/buku/{id}/terima', [TerimaBukuController::class, 'terimaBuku'])->name('terima-buku');
    Route::put('/buku/{id}/tolak', [TerimaBukuController::class, 'tolakBuku'])->name('tolak-buku');

    //terima buku
    Route::get('/terima-isi-admin', [TerimaIsiController::class, 'index'])->name('terima-isi.index');
    Route::put('/isi/{id}/terima', [TerimaIsiController::class, 'terimaIsi'])->name('terima-isi');
    Route::put('/isi/{id}/tolak', [TerimaIsiController::class, 'tolakIsi'])->name('tolak-isi');


    //User
    //Dashboard
    Route::get('/dashboardUser', [DashboardController::class, 'indexUser'])->name('dashboard.user.index');

    //tambah buku 
    Route::get('/tambahkan-buku', [TambahBukuController::class, 'index'])->name('tambah-buku.index');
    Route::post('/tambah-buku', [TambahBukuController::class, 'store'])->name('buku.store');
    Route::put('/buku/{buku}', [TambahBukuController::class, 'update'])->name('buku.update');
    Route::delete('/buku/{buku}', [TambahBukuController::class, 'destroy'])->name('buku.destroy');

    //tambah isi 
    Route::get('/tambahkan-isi/{id_buku}', [TambahIsiController::class, 'index'])->name('tambah-isi.index');
    Route::post('/tambah-isi', [TambahIsiController::class, 'store'])->name('isi.store');
    Route::put('/tambah-isi/{id}', [TambahIsiController::class, 'update'])->name('tambah-isi.update');
    Route::delete('/tambah-isi/{id}', [TambahIsiController::class, 'destroy'])->name('tambah-isi.destroy');


});

