<?php

namespace App\Jobs;

use App\Models\Appointment;
use App\Services\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendAppointmentReminder implements ShouldQueue
{
    use Queueable;

    protected $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function handle(): void
    {
        $patient = $this->appointment->patient->user;
        $doctor = $this->appointment->doctor->user;
        $date = Carbon::parse($this->appointment->date)->format('d/m/Y');
        $time = Carbon::parse($this->appointment->start_time)->format('H:i');

        $message = "⏰ *Recordatorio de Cita Médica*\n\n"
            . "Hola {$patient->name}, te recordamos que mañana tienes una cita.\n\n"
            . "👨‍⚕️ Doctor: Dr. {$doctor->name}\n"
            . "📅 Fecha: {$date}\n"
            . "🕐 Hora: {$time}\n\n"
            . "Por favor llega 10 minutos antes. ¡Te esperamos!";

        $whatsapp = new WhatsAppService();
        $whatsapp->sendMessage($patient->phone, $message);
    }
}