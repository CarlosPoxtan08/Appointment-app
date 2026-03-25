<?php

namespace App\Console\Commands;

use App\Mail\DailyAppointmentReport;
use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendDailyAppointmentReport extends Command
{
    protected $signature   = 'appointments:daily-report';
    protected $description = 'Sends the daily appointment report to the admin and each doctor';

    public function handle(): int
    {
        $today = now()->toDateString();

        $appointments = Appointment::whereDate('date', $today)
            ->with(['patient.user', 'doctor.user', 'specialty'])
            ->get();

        // Enviar al administrador
        $adminEmail = env('ADMIN_EMAIL', 'admin@example.com');

        Mail::to($adminEmail)->send(
            new DailyAppointmentReport($appointments, 'Administrador')
        );

        $this->info("✅ Report sent to admin ({$adminEmail}) — {$appointments->count()} appointments.");

        // Enviar a cada doctor su lista personal
        $doctors = Doctor::whereHas('appointments', function ($q) use ($today) {
            $q->whereDate('date', $today);
        })->with('user')->get();

        foreach ($doctors as $doctor) {
            $doctorAppointments = $appointments->where('doctor_id', $doctor->id)->values();

            Mail::to($doctor->user->email)->send(
                new DailyAppointmentReport($doctorAppointments, 'Dr. ' . $doctor->user->name)
            );

            $this->info("✅ Report sent to Dr. {$doctor->user->name} ({$doctor->user->email}).");
        }

        return Command::SUCCESS;
    }
}