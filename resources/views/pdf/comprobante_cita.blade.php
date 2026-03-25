<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; color: #333; margin: 0; padding: 0; }
        .header { background-color: #1e40af; color: white; padding: 24px; text-align: center; }
        .header h1 { margin: 0; font-size: 22px; }
        .header p { margin: 4px 0 0; font-size: 13px; opacity: 0.85; }
        .content { padding: 30px; }
        h3 { color: #1e40af; border-bottom: 2px solid #1e40af; padding-bottom: 6px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        td, th { border: 1px solid #ddd; padding: 10px 14px; font-size: 13px; }
        th { background-color: #1e40af; color: white; text-align: left; }
        tr:nth-child(even) { background-color: #f1f5f9; }
        .footer { margin-top: 40px; text-align: center; font-size: 11px; color: #999; border-top: 1px solid #eee; padding-top: 16px; }
        .badge { display: inline-block; background: #dcfce7; color: #166534; padding: 3px 10px; border-radius: 12px; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🏥 Comprobante de Cita Médica</h1>
        <p>Folio: #{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}</p>
    </div>

    <div class="content">
        <h3>Datos del Paciente</h3>
        <table>
            <tr><th>Nombre</th><td>{{ $appointment->patient->user->name }}</td></tr>
            <tr><th>Email</th><td>{{ $appointment->patient->user->email }}</td></tr>
            <tr><th>Género</th><td>{{ ucfirst($appointment->patient->gender) }}</td></tr>
            <tr><th>Fecha de Nacimiento</th><td>{{ $appointment->patient->date_of_birth->format('d/m/Y') }}</td></tr>
        </table>

        <h3 style="margin-top:24px;">Detalles de la Cita</h3>
        <table>
            <tr><th>Doctor</th><td>{{ $appointment->doctor->user->name }}</td></tr>
            <tr><th>Especialidad</th><td>{{ $appointment->specialty->name ?? 'No especificada' }}</td></tr>
            <tr><th>Fecha</th><td>{{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}</td></tr>
            <tr><th>Hora</th><td>{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }} — {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}</td></tr>
            <tr><th>Estado</th><td><span class="badge">{{ $appointment->status }}</span></td></tr>
            <tr><th>Notas</th><td>{{ $appointment->notes ?? 'Sin notas adicionales' }}</td></tr>
        </table>

        <div class="footer">
            <p>Preséntalo el día de tu cita. Generado el {{ now()->format('d/m/Y \a \l\a\s H:i') }}</p>
            <p>{{ config('app.name') }} — Sistema de Gestión de Citas Médicas</p>
        </div>
    </div>
</body>
</html>