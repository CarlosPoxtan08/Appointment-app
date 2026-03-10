<?php

namespace App\Livewire\Admin\Appointments;

use Livewire\Component;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Specialty;
use App\Models\Schedule;
use App\Models\Appointment;
use Carbon\Carbon;

class EditAppointment extends Component
{
    public Appointment $appointment;
    
    public $patient_id;
    public $doctor_id;
    public $specialty_id;
    public $date;
    public $start_time;
    public $status;

    public function mount(Appointment $appointment)
    {
        $this->appointment = $appointment;
        $this->patient_id = $appointment->patient_id;
        $this->doctor_id = $appointment->doctor_id;
        $this->specialty_id = $appointment->specialty_id;
        $this->date = \Carbon\Carbon::parse($appointment->date)->format('Y-m-d');
        $this->start_time = \Carbon\Carbon::parse($appointment->start_time)->format('H:i');
        $this->status = $appointment->status;
    }

    public function rules()
    {
        return [
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'specialty_id' => 'nullable|exists:specialties,id',
            'date' => 'required|date',
            'start_time' => 'required',
            'status' => 'required|in:Programado,Completado,Cancelado',
        ];
    }

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
        if (!$this->date || (!$this->doctor_id && !$this->appointment->doctor_id)) {
            return [];
        }

        $doctorId = $this->doctor_id ?: $this->appointment->doctor_id;
        $carbonDate = Carbon::parse($this->date);
        $dayOfWeek = $carbonDate->dayOfWeek; 

        $schedules = Schedule::where('doctor_id', $doctorId)
            ->where('day_of_week', (string)$dayOfWeek)
            ->get();

        if ($schedules->isEmpty()) {
            // Keep the current appointment time available even if out of schedule
            if ($this->doctor_id == $this->appointment->doctor_id && $this->date == \Carbon\Carbon::parse($this->appointment->date)->format('Y-m-d')) {
                return [\Carbon\Carbon::parse($this->appointment->start_time)->format('H:i')];
            }
            return [];
        }

        $existingAppointments = Appointment::where('doctor_id', $doctorId)
            ->whereDate('date', $this->date)
            ->where('id', '!=', $this->appointment->id) // Ignore current appointment
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
                foreach ($existingAppointments as $app) {
                    if (($slotStart >= $app->start_time && $slotStart < $app->end_time) ||
                        ($slotEnd > $app->start_time && $slotEnd <= $app->end_time) ||
                        ($slotStart <= $app->start_time && $slotEnd >= $app->end_time)) {
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
        
        // Ensure current time is always an option if the dates and doctor match
        $currentStartOption = \Carbon\Carbon::parse($this->appointment->start_time)->format('H:i');
        if ($this->doctor_id == $this->appointment->doctor_id && 
            $this->date == \Carbon\Carbon::parse($this->appointment->date)->format('Y-m-d') &&
            !in_array($currentStartOption, $slots)) {
            $slots[] = $currentStartOption;
            sort($slots);
        }

        return $slots;
    }

    public function save()
    {
        $this->validate();

        $endTime = Carbon::parse($this->start_time)->addMinutes(40)->format('H:i');

        $this->appointment->update([
            'patient_id' => $this->patient_id,
            'doctor_id' => $this->doctor_id,
            'specialty_id' => $this->specialty_id ?: null,
            'date' => $this->date,
            'start_time' => $this->start_time,
            'end_time' => $endTime,
            'status' => $this->status,
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Éxito',
            'text' => 'Cita actualizada exitosamente.'
        ]);

        return redirect()->route('admin.appointments.index');
    }

    public function render()
    {
        $patients = Patient::with('user')->get();
        $doctors = Doctor::with('user', 'specialty')->get();
        $specialties = Specialty::all();

        return view('livewire.admin.appointments.edit-appointment', compact('patients', 'doctors', 'specialties'))
            ->layout('layouts.admin', [
                'title' => 'Editar Cita',
                'breadcrumbs' => [
                    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
                    ['name' => 'Citas', 'href' => route('admin.appointments.index')],
                    ['name' => 'Editar'],
                ]
            ]);
    }
}
