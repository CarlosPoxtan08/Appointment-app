<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DoctorController;

Route::redirect('/', '/admin');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/doctors', [DoctorController::class, 'index'])->name('doctors.index');
        Route::get('/doctors/{id}/edit', [DoctorController::class, 'edit'])->name('doctors.edit');
        Route::put('/doctors/{id}', [DoctorController::class, 'update'])->name('doctors.update');
    });
});