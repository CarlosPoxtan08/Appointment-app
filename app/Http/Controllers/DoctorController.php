<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::with(['user', 'specialty'])->get();
        return view('admin.doctors.index', compact('doctors'));
    }

    public function edit($id)
    {
        $doctor = Doctor::with(['user', 'specialty'])->findOrFail($id);
        $specialties = Specialty::all();
        return view('admin.doctors.edit', compact('doctor', 'specialties'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'specialty_id' => 'required|exists:specialties,id',
            'license' => 'nullable|string|max:255',
            'biography' => 'nullable|string',
        ], [
            'specialty_id.required' => 'La especialidad es obligatoria.',
            'specialty_id.exists' => 'La especialidad seleccionada no es válida.',
            'license.max' => 'La cédula profesional no debe exceder los 255 caracteres.',
            'license.string' => 'La cédula profesional debe ser texto.',
            'biography.string' => 'La biografía debe ser texto.',
        ]);

        $doctor = Doctor::findOrFail($id);
        $doctor->update([
            'specialty_id' => $request->specialty_id,
            'license' => $request->license,
            'biography' => $request->biography,
        ]);

        return redirect()->route('admin.doctors.index')->with('swal', [
            'title' => '¡Éxito!',
            'text' => 'Doctor actualizado correctamente.',
            'icon' => 'success',
        ]);
    }
}