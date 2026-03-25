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
    <h1>📋 Nueva cita agendada</h1>
    <p>Dr. <strong>{{ $appointment->doctor->user->name }}</strong>,</p>
    <p>Se ha registrado una nueva cita en tu agenda.</p>

    <table>
        <tr><th>Paciente</th><td>{{ $appointment->patient->user->name }}</td></tr>
        <tr><th>Especialidad</th><td>{{ $appointment->specialty->name ?? 'No especificada' }}</td></tr>
        <tr><th>Fecha</th><td>{{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}</td></tr>
        <tr><th>Hora</th><td>{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }} — {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}</td></tr>
        <tr><th>Notas</th><td>{{ $appointment->notes ?? 'Sin notas' }}</td></tr>
    </table>

    <p>📎 Se adjunta el comprobante de la cita en PDF.</p>

    <div class="footer">
        <p>{{ config('app.name') }} — Sistema de Gestión de Citas Médicas</p>
    </div>
</body>
</html>