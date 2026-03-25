<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; color: #333; padding: 20px; }
        h1 { color: #1e40af; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th { background-color: #1e40af; color: white; padding: 10px; text-align: left; }
        td { padding: 10px; border: 1px solid #ddd; }
        tr:nth-child(even) { background-color: #f1f5f9; }
        .footer { margin-top: 30px; font-size: 12px; color: #999; }
    </style>
</head>
<body>
    <h1>✅ Tu cita ha sido confirmada</h1>
    <p>Hola <strong>{{ $appointment->patient->user->name }}</strong>,</p>
    <p>Tu cita médica ha sido registrada exitosamente en nuestro sistema.</p>

    <table>
        <tr><th>Doctor</th><td>{{ $appointment->doctor->user->name }}</td></tr>
        <tr><th>Especialidad</th><td>{{ $appointment->specialty->name ?? 'No especificada' }}</td></tr>
        <tr><th>Fecha</th><td>{{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}</td></tr>
        <tr><th>Hora</th><td>{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}</td></tr>
        <tr><th>Estado</th><td>{{ $appointment->status }}</td></tr>
        <tr><th>Notas</th><td>{{ $appointment->notes ?? 'Sin notas' }}</td></tr>
    </table>

    <p>📎 Adjunto encontrarás tu <strong>comprobante en PDF</strong>. Preséntalo el día de tu cita.</p>

    <div class="footer">
        <p>{{ config('app.name') }} — Sistema de Gestión de Citas Médicas</p>
    </div>
</body>
</html>