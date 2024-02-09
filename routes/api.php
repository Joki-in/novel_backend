<?php
use App\Http\Controllers\Api\CreateBukuController;
use App\Http\Controllers\Api\CreateBukuPageController;
use App\Http\Controllers\Api\EditBukuController;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\IsiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BukuController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\KomentarController;
use App\Http\Controllers\Api\ForgotPasswordController;
use App\Http\Controllers\Api\NovelPageTampilController;
use App\Http\Controllers\Api\PushLikeAndViewController;

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

//PushLikeAndViewController routes
Route::post('/tambah-view', [PushLikeAndViewController::class, 'tambahView']);
Route::post('/check-like', [PushLikeAndViewController::class, 'checkLike']);
Route::post('/tambah-like', [PushLikeAndViewController::class, 'store']);
Route::post('/hapus-like', [PushLikeAndViewController::class, 'destroy']);

//KomentarController routes
Route::post('/komentarpost', [KomentarController::class, 'store']);

//IsiController routes
Route::post('/getisibuku', [IsiController::class, 'IsiByIdBuku']);

//CreateBukuController routes
Route::post('/createbuku', [CreateBukuController::class, 'store']);
Route::post('/updatecover', [CreateBukuController::class, 'updateCover']);
Route::post('/createIsi', [CreateBukuController::class, 'createIsi']);

Route::post('/updatecover', [EditBukuController::class, 'updateCover']);

//create buku page routes
Route::post('/createbukushow', [CreateBukuPageController::class, 'createBukuPageShow']);
Route::post('/deletebuku', [CreateBukuPageController::class, 'deleteDataByBukuId']);
Route::post('/delete-isi', [EditBukuController::class, 'deleteIsiByIdFromBody']);
Route::post('/update-isi', [EditBukuController::class, 'editIsi']);
