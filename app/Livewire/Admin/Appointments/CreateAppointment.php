<?php

namespace App\Livewire\Admin\Appointments;

use Livewire\Component;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Specialty;
use App\Models\Schedule;
use App\Models\Appointment;
use Carbon\Carbon;

class CreateAppointment extends Component
{
    public $patient_id;
    public $doctor_id;
    public $specialty_id;
    public $date;
    public $start_time;

    public function rules()
    {
        return [
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'specialty_id' => 'nullable|exists:specialties,id',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
        ];
    }

    // Al actualizar el doctor o la fecha, resetear la hora
    public function updatedDoctorId()
    {
        $this->start_time = null;
    }

    public function updatedDate()
    {
        $this->start_time = null;
    }

    public function getAvailableSlotsProperty()
    {
        if (!$this->date || !$this->doctor_id) {
            return [];
        }

        $carbonDate = Carbon::parse($this->date);
        $dayOfWeek = $carbonDate->dayOfWeek; // 0 Sunday, 1 Monday, ...

        $schedules = Schedule::where('doctor_id', $this->doctor_id)
            ->where('day_of_week', (string)$dayOfWeek)
            ->get();

        if ($schedules->isEmpty()) {
            return [];
        }

        $existingAppointments = Appointment::where('doctor_id', $this->doctor_id)
            ->whereDate('date', $this->date)
            ->where('status', '!=', 'Cancelado')
            ->get();

        $slots = [];
        $duration = 40;

        foreach ($schedules as $schedule) {
            $startTime = Carbon::parse($schedule->start_time);
            $endTime = Carbon::parse($schedule->end_time);

            while ($startTime->copy()->addMinutes($duration)->lte($endTime)) {
                $slotStart = $startTime->format('H:i:s');
                $slotEnd = $startTime->copy()->addMinutes($duration)->format('H:i:s');

                $isAvailable = true;
                foreach ($existingAppointments as $appointment) {
                    if (($slotStart >= $appointment->start_time && $slotStart < $appointment->end_time) ||
                        ($slotEnd > $appointment->start_time && $slotEnd <= $appointment->end_time) ||
                        ($slotStart <= $appointment->start_time && $slotEnd >= $appointment->end_time)) {
                        $isAvailable = false;
                        break;
                    }
                }

                if ($isAvailable) {
                    $slots[] = $startTime->format('H:i');
                }

                $startTime->addMinutes($duration);
            }
        }

        return $slots;
    }

    public function save()
    {
        $this->validate();

        $endTime = Carbon::parse($this->start_time)->addMinutes(40)->format('H:i');

        Appointment::create([
            'patient_id' => $this->patient_id,
            'doctor_id' => $this->doctor_id,
            'specialty_id' => $this->specialty_id ?: null,
            'date' => $this->date,
            'start_time' => $this->start_time,
            'end_time' => $endTime,
            'status' => 'Programado',
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Éxito',
            'text' => 'Cita creada exitosamente.'
        ]);

        return redirect()->route('admin.appointments.index');
    }

    public function render()
    {
        $patients = Patient::with('user')->get();
        $doctors = Doctor::with('user', 'specialty')->get();
        $specialties = Specialty::all();

        return view('livewire.admin.appointments.create-appointment', compact('patients', 'doctors', 'specialties'))
            ->layout('layouts.admin', [
                'title' => 'Nueva Cita',
                'breadcrumbs' => [
                    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
                    ['name' => 'Citas', 'href' => route('admin.appointments.index')],
                    ['name' => 'Nuevo'],
                ]
            ]);
    }
}
