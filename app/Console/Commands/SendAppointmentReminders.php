<?php

namespace App\Console\Commands;

use App\Jobs\SendAppointmentReminder;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendAppointmentReminders extends Command
{
    protected $signature = 'appointments:send-reminders';
    protected $description = 'Envía recordatorios de cita por WhatsApp un día antes';

    public function handle(): void
    {
        $tomorrow = Carbon::tomorrow()->toDateString();

        $appointments = Appointment::with(['patient.user', 'doctor.user'])
            ->where('date', $tomorrow)
            ->where('status', 'Programado')
            ->get();

        foreach ($appointments as $appointment) {
            SendAppointmentReminder::dispatch($appointment);
        }

        $this->info("✅ Se enviaron {$appointments->count()} recordatorios.");
    }
}