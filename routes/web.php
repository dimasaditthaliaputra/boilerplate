<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['guest'])->group(function () {
    Route::get('login', [App\Http\Controllers\Auth\AuthController::class, 'index'])->name('login');
    Route::post('login', [App\Http\Controllers\Auth\AuthController::class, 'login'])->name('login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', function () {
        return view('admin.dashboard.index');
    })->name('dashboard');

    Route::post('/logout', [App\Http\Controllers\Auth\AuthController::class, 'logout'])->name('logout');
});

Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::resource('manajemen-menu', App\Http\Controllers\Setting\MenuController::class);
    Route::resource('manajemen-hak-akses', App\Http\Controllers\Setting\HakAksesController::class);
    Route::resource('manajemen-role', App\Http\Controllers\Setting\RoleController::class);

    Route::get('testing', function () {
        return view('admin.testing.index');
    })->name('testing');
});
