<?php

namespace App\Livewire\Admin\Doctors;

use Livewire\Component;
use App\Models\Doctor;
use App\Models\Schedule;

class ScheduleForm extends Component
{
    public Doctor $doctor;
    public $schedules = [];
    
    // Arrays for day of week options
    public $days = [
        0 => 'Domingo',
        1 => 'Lunes',
        2 => 'Martes',
        3 => 'Miércoles',
        4 => 'Jueves',
        5 => 'Viernes',
        6 => 'Sábado',
    ];

    public function mount(Doctor $doctor)
    {
        $this->doctor = $doctor;
        $this->loadSchedules();
    }

    public function loadSchedules()
    {
        $existing = Schedule::where('doctor_id', $this->doctor->id)->get();
        $this->schedules = [];
        
        foreach ($existing as $schedule) {
            $this->schedules[] = [
                'id' => $schedule->id,
                'day_of_week' => $schedule->day_of_week,
                'start_time' => \Carbon\Carbon::parse($schedule->start_time)->format('H:i'),
                'end_time' => \Carbon\Carbon::parse($schedule->end_time)->format('H:i'),
            ];
        }

        // Add an empty row if none exist
        if (empty($this->schedules)) {
            $this->addSchedule();
        }
    }

    public function addSchedule()
    {
        $this->schedules[] = [
            'id' => null,
            'day_of_week' => '1',
            'start_time' => '09:00',
            'end_time' => '17:00',
        ];
    }

    public function removeSchedule($index)
    {
        $scheduleId = $this->schedules[$index]['id'] ?? null;
        if ($scheduleId) {
            Schedule::find($scheduleId)->delete();
        }
        unset($this->schedules[$index]);
        $this->schedules = array_values($this->schedules);
    }

    public function save()
    {
        $this->validate([
            'schedules.*.day_of_week' => 'required',
            'schedules.*.start_time' => 'required|date_format:H:i',
            'schedules.*.end_time' => 'required|date_format:H:i|after:schedules.*.start_time',
        ]);

        foreach ($this->schedules as $schedule) {
            if (isset($schedule['id'])) {
                Schedule::where('id', $schedule['id'])->update([
                    'day_of_week' => $schedule['day_of_week'],
                    'start_time' => $schedule['start_time'],
                    'end_time' => $schedule['end_time'],
                ]);
            } else {
                Schedule::create([
                    'doctor_id' => $this->doctor->id,
                    'day_of_week' => $schedule['day_of_week'],
                    'start_time' => $schedule['start_time'],
                    'end_time' => $schedule['end_time'],
                ]);
            }
        }
        
        $this->loadSchedules();
        
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Éxito',
            'text' => 'Horario actualizado correctamente.',
            'toast' => true,
            'position' => 'top-end',
            'showConfirmButton' => false,
            'timer' => 3000
        ]);
        
        // This line makes Livewire dispatch an event that Alpine/JS could listen to (optional for swal)
        $this->dispatch('swal-trigger'); 
    }

    public function render()
    {
        return view('livewire.admin.doctors.schedule-form');
    }
}
