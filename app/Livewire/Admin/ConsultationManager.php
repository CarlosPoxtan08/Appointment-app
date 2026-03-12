<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Appointment;
use App\Models\PrescriptionItem;

class ConsultationManager extends Component
{
    public Appointment $appointment;

    public $activeTab = 'consulta';
    public $diagnosis;
    public $treatment;
    public $notes;
    public $showPastConsultationsModal = false;

    public $prescriptionItems = [];

    public function mount(Appointment $appointment)
    {
        $this->appointment = $appointment;
        $this->diagnosis = $appointment->diagnosis;
        $this->treatment = $appointment->treatment;
        $this->notes = $appointment->notes;

        foreach ($appointment->prescriptionItems as $item) {
            $this->prescriptionItems[] = [
                'id' => $item->id,
                'medicine_name' => $item->medicine_name,
                'dosage' => $item->dosage,
                'frequency' => $item->frequency,
            ];
        }

        if (empty($this->prescriptionItems)) {
            $this->addPrescriptionItem();
        }
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function addPrescriptionItem()
    {
        $this->prescriptionItems[] = [
            'id' => null,
            'medicine_name' => '',
            'dosage' => '',
            'frequency' => '',
        ];
    }

    public function removePrescriptionItem($index)
    {
        if (isset($this->prescriptionItems[$index]['id']) && $this->prescriptionItems[$index]['id'] !== null) {
            PrescriptionItem::find($this->prescriptionItems[$index]['id'])->delete();
        }
        unset($this->prescriptionItems[$index]);
        $this->prescriptionItems = array_values($this->prescriptionItems); // Re-index array
    }

    public function saveConsultation()
    {
        $this->validate([
            'diagnosis' => 'nullable|string',
            'treatment' => 'nullable|string',
            'notes' => 'nullable|string',
            'prescriptionItems.*.medicine_name' => 'required|string',
            'prescriptionItems.*.dosage' => 'required|string',
            'prescriptionItems.*.frequency' => 'nullable|string',
        ], [
            'prescriptionItems.*.medicine_name.required' => 'El nombre del medicamento es obligatorio.',
            'prescriptionItems.*.dosage.required' => 'La dosis es obligatoria.',
        ]);

        $this->appointment->update([
            'diagnosis' => $this->diagnosis,
            'treatment' => $this->treatment,
            'notes' => $this->notes,
            'status' => 'Completado', // Auto completar al guardar consulta
        ]);

        foreach ($this->prescriptionItems as $item) {
            if ($item['id']) {
                $prescription = PrescriptionItem::find($item['id']);
                $prescription->update([
                    'medicine_name' => $item['medicine_name'],
                    'dosage' => $item['dosage'],
                    'frequency' => $item['frequency'],
                ]);
            } else {
                if (!empty($item['medicine_name']) && !empty($item['dosage'])) {
                    PrescriptionItem::create([
                        'appointment_id' => $this->appointment->id,
                        'medicine_name' => $item['medicine_name'],
                        'dosage' => $item['dosage'],
                        'frequency' => $item['frequency'],
                    ]);
                }
            }
        }

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Guardado',
            'text' => 'Los datos de la consulta han sido guardados correctamente.'
        ]);

        return redirect()->route('admin.appointments.index');
    }

    public function render()
    {
        $pastAppointments = Appointment::where('patient_id', $this->appointment->patient_id)
            ->where('id', '!=', $this->appointment->id)
            ->where('status', 'Completado')
            ->orderBy('date', 'desc')
            ->get();

        return view('livewire.admin.consultation-manager', compact('pastAppointments'))
            ->layout('layouts.admin', [
                'title' => 'Consulta',
                'breadcrumbs' => [
                    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
                    ['name' => 'Citas', 'href' => route('admin.appointments.index')],
                    ['name' => 'Consulta'],
                ]
            ]);
    }
}
