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
    <h1>📅 Reporte de Citas — {{ $fecha }}</h1>
    <p>Hola <strong>{{ $recipientName }}</strong>, aquí tienes las citas agendadas para hoy.</p>

    @if($appointments->isEmpty())
        <p>ℹ️ No hay citas agendadas para el día de hoy.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Paciente</th>
                    <th>Doctor</th>
                    <th>Hora</th>
                    <th>Especialidad</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($appointments as $a)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $a->patient->user->name }}</td>
                    <td>{{ $a->doctor->user->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($a->start_time)->format('H:i') }}</td>
                    <td>{{ $a->specialty->name ?? '—' }}</td>
                    <td>{{ $a->status }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <p><strong>Total de citas hoy: {{ $appointments->count() }}</strong></p>
    @endif

    <div class="footer">
        <p>{{ config('app.name') }} — Sistema de Gestión de Citas Médicas</p>
    </div>
</body>
</html>