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

        //Appointments
        Route::view('/appointments', 'admin.appointments.index')->name('appointments.index');
        Route::get('/appointments/create', \App\Livewire\Admin\Appointments\CreateAppointment::class)->name('appointments.create');
        Route::get('/appointments/{appointment}/edit', \App\Livewire\Admin\Appointments\EditAppointment::class)->name('appointments.edit');
        Route::delete('/appointments/{appointment}', function (\App\Models\Appointment $appointment) {
            $appointment->delete();
            session()->flash('swal', [
                'icon' => 'success',
                'title' => 'Cita Eliminada',
                'text' => 'La cita ha sido eliminada correctamente.'
            ]);
            return redirect()->route('admin.appointments.index');
        })->name('appointments.destroy');
    });
});