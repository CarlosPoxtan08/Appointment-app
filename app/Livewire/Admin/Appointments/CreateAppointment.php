<?php

namespace App\Livewire\Admin\Appointments;

use Livewire\Component;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Specialty;
use App\Models\Appointment;
use App\Models\Schedule;
use Carbon\Carbon;

class CreateAppointment extends Component
{
    // Search Criteria
    public $searchDate;
    public $searchTimeRange = '';
    public $searchSpecialty = '';

    // Search Results
    public $availableDoctors = [];

    // Selected Slot Details
    public $selectedDoctorId = null;
    public $selectedDoctorName = null;
    public $selectedTime = null;
    
    // Form Details
    public $patient_id = '';
    public $notes = '';

    public function mount()
    {
        $this->searchDate = Carbon::tomorrow()->format('Y-m-d');
    }

    public function searchAvailability()
    {
        $this->validate([
            'searchDate' => 'required|date',
        ]);

        $dayOfWeek = Carbon::parse($this->searchDate)->dayOfWeek;

        $query = Doctor::with(['user', 'specialty']);
        
        if ($this->searchSpecialty) {
            $query->where('specialty_id', $this->searchSpecialty);
        }

        $doctors = $query->get();
        $this->availableDoctors = [];

        foreach ($doctors as $doctor) {
            $schedules = Schedule::where('doctor_id', $doctor->id)
                ->where('day_of_week', $dayOfWeek)
                ->get();
            
            $slots = [];
            foreach ($schedules as $schedule) {
                // If a time range was selected, we could filter here. For now we generate all slots.
                $start = Carbon::parse($schedule->start_time);
                $end = Carbon::parse($schedule->end_time);

                // Example: 15 min slots
                while ($start->copy()->addMinutes(15)->lte($end)) {
                    $timeString = $start->format('H:i');
                    
                    // Filter by search time range roughly if specified
                    if ($this->searchTimeRange) {
                        [$rangeStart, $rangeEnd] = explode('-', str_replace(' ', '', $this->searchTimeRange));
                        if ($timeString >= $rangeStart && $timeString < $rangeEnd) {
                            $slots[] = $timeString;
                        }
                    } else {
                        $slots[] = $timeString;
                    }
                    
                    $start->addMinutes(15);
                }
            }

            if (count($slots) > 0) {
                $this->availableDoctors[] = [
                    'id' => $doctor->id,
                    'name' => 'Dr. ' . $doctor->user->name,
                    'initials' => collect(explode(' ', $doctor->user->name))->map(fn($n) => substr($n,0,1))->take(2)->implode(''),
                    'specialty' => optional($doctor->specialty)->name,
                    'slots' => $slots
                ];
            }
        }
        
        $this->resetSelection();
    }

    public function selectSlot($doctorId, $doctorName, $time)
    {
        $this->selectedDoctorId = $doctorId;
        $this->selectedDoctorName = $doctorName;
        $this->selectedTime = $time;
    }

    public function resetSelection()
    {
        $this->selectedDoctorId = null;
        $this->selectedDoctorName = null;
        $this->selectedTime = null;
    }

    public function save()
    {
        $this->validate([
            'selectedDoctorId' => 'required',
            'searchDate' => 'required',
            'selectedTime' => 'required',
            'patient_id' => 'required|exists:patients,id',
            'notes' => 'nullable|string',
        ], [
            'selectedDoctorId.required' => 'Debes seleccionar un horario.',
            'patient_id.required' => 'El paciente es obligatorio.',
        ]);

        $startTime = Carbon::parse($this->searchDate . ' ' . $this->selectedTime);
        $endTime = $startTime->copy()->addMinutes(15);

        Appointment::create([
            'patient_id' => $this->patient_id,
            'doctor_id' => $this->selectedDoctorId,
            'specialty_id' => Doctor::find($this->selectedDoctorId)->specialty_id ?? null,
            'date' => $this->searchDate,
            'start_time' => $startTime->format('H:i:s'),
            'end_time' => $endTime->format('H:i:s'),
            'notes' => $this->notes,
            'status' => 'Programado',
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Cita Confirmada',
            'text' => 'Cita creada exitosamente.'
        ]);

        return redirect()->route('admin.appointments.index');
    }

    public function render()
    {
        $patients = Patient::with('user')->get();
        $specialties = Specialty::all();

        return view('livewire.admin.appointments.create-appointment', compact('patients', 'specialties'))
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
