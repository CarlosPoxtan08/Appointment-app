<?php

namespace App\Mail;

use App\Models\Appointment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AppointmentConfirmedPatient extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Appointment $appointment) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmación de Cita Médica — Folio #' . str_pad($this->appointment->id, 6, '0', STR_PAD_LEFT),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.appointment_confirmed_patient',
        );
    }

    public function attachments(): array
    {
        $pdf = Pdf::loadView('pdf.comprobante_cita', [
            'appointment' => $this->appointment,
        ]);

        return [
            Attachment::fromData(
                fn () => $pdf->output(),
                'comprobante_cita_' . str_pad($this->appointment->id, 6, '0', STR_PAD_LEFT) . '.pdf'
            )->withMime('application/pdf'),
        ];
    }
}