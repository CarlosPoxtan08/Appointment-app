<?php

namespace App\Livewire\Admin\DataTables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Appointment;
use Illuminate\Database\Eloquent\Builder;

class AppointmentTable extends DataTableComponent
{
    public function builder(): Builder
    {
        return Appointment::query()->with(['patient.user', 'doctor.user']);
    }

    protected $model = Appointment::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->sortable()
                ->searchable(),
            Column::make('Paciente', 'patient.user.name')
                ->sortable()
                ->searchable(),
            Column::make('Doctor', 'doctor.user.name')
                ->sortable()
                ->searchable(),
            Column::make('Fecha', 'date')
                ->sortable()
                ->format(fn($value) => \Carbon\Carbon::parse($value)->format('d/m/Y')),
            Column::make('Hora', 'start_time')
                ->sortable()
                ->format(fn($value) => \Carbon\Carbon::parse($value)->format('H:i')),
            Column::make('Hora Fin', 'end_time')
                ->sortable()
                ->format(fn($value) => \Carbon\Carbon::parse($value)->format('H:i')),
            Column::make('Estado', 'status')
                ->sortable()
                ->searchable(),
            Column::make('Acciones', 'id')
                ->format(function($value, $row, \Rappasoft\LaravelLivewireTables\Views\Column $column) {
                    return view('admin.appointments.actions', ['appointment' => $row]);
                })
                ->html(),
        ];
    }
}
