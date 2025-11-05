<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PoliController;
use App\Http\Controllers\Pasien\PoliController as PasienPoliController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\JadwalPeriksaController;
use Illuminate\Support\Facades\Route;

// Redirect dari root '/' ke halaman login
Route::get('/', function () {
    return redirect()->route('login');
});


Route::middleware('guest')->group(function () {
    // Tampilkan form login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    // Proses login
    Route::post('/login', [AuthController::class, 'login']);

    // Tampilkan form register
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    // Proses registrasi
    Route::post('/register', [AuthController::class, 'register']);
});

// Route Logout (Middleware 'auth')
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');


Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function() {
    // Dashboard Admin
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // CRUD Modules
    Route::resource('polis', PoliController::class);
    Route::resource('dokter', DokterController::class);
    Route::resource('pasien', PasienController::class);
    Route::resource('obat', ObatController::class);
});

Route::middleware(['auth', 'role:dokter'])->prefix('dokter')->group(function(){
    // Dashboard Dokter
    Route::get('/dashboard', function () {
        return view('dokter.dashboard');
    })->name('dokter.dashboard');

    // CRUD Jadwal Periksa
    Route::resource('jadwal-periksa', JadwalPeriksaController::class);
});


Route::middleware(['auth', 'role:pasien'])->prefix('pasien')->group(function(){
    // Dashboard Pasien
    Route::get('/dashboard', function () {
        return view('pasien.dashboard');
    })->name('pasien.dashboard');

    // Pendaftaran Poli
    Route::get('/daftar', [PasienPoliController::class, 'get'])->name('pasien.daftar');
    Route::post('/daftar', [PasienPoliController::class, 'submit'])->name('pasien.daftar.submit');
});
