<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\AlbumController;
use App\Http\Controllers\API\BeritaController;
use App\Http\Controllers\API\BidangController;
use App\Http\Controllers\API\DetailBidangController;
use App\Http\Controllers\API\DokumentasiController;
use App\Http\Controllers\API\DownloadController;
use App\Http\Controllers\API\HeaderBidangController;
use App\Http\Controllers\API\HeroController;
use App\Http\Controllers\API\HomeController;
use App\Http\Controllers\API\KategoriController;
use App\Http\Controllers\API\KontakController;
use App\Http\Controllers\API\OtherContactController;
use App\Http\Controllers\API\PengumumanController;
use App\Http\Controllers\API\PengurusController;
use App\Http\Controllers\API\PeranPengurusController;
use App\Http\Controllers\API\ProfilController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\VideoController;

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

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login'])->middleware('api');
        Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
    });
    Route::get('me', [AuthController::class, 'getUser'])->middleware('auth:api');
    Route::apiResource("album-kegiatan", AlbumController::class);
    Route::apiResource("berita", BeritaController::class);
    Route::apiResource("bidang", BidangController::class);
    Route::apiResource("detail-bidang", DetailBidangController::class);
    Route::apiResource("dokumentasi", DokumentasiController::class);
    Route::apiResource("download", DownloadController::class);
    Route::apiResource("header-bidang", HeaderBidangController::class);
    Route::apiResource("hero", HeroController::class);
    Route::apiResource("home", HomeController::class);
    Route::apiResource("kategori-berita", KategoriController::class);
    Route::apiResource("kontak", KontakController::class);
    Route::apiResource("other-contact", OtherContactController::class);
    Route::apiResource("pengumuman", PengumumanController::class);
    Route::apiResource("pengurus", PengurusController::class);
    Route::apiResource("peran-pengurus", PeranPengurusController::class);
    Route::apiResource("profil", ProfilController::class);
    Route::apiResource("role", RoleController::class)->middleware(['auth:api', 'isAdmin']);
    Route::apiResource("user", UserController::class)->middleware(['auth:api', 'isAdmin']);
    Route::apiResource("video", VideoController::class);
    Route::get("dashboard-berita", [BeritaController::class, 'dashboardberita']);
    Route::get("dashboard-pengumuman", [PengumumanController::class, 'dashboardpengumuman']);
    Route::get("dashboard-video", [VideoController::class, 'dashboardvideo']);
});
