<div>
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-xl font-semibold text-gray-900 mb-4">Detalles de la Cita</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div>
                <label for="patient_id" class="block mb-2 text-sm font-medium text-gray-900">Paciente <span class="text-red-500">*</span></label>
                <select id="patient_id" wire:model="patient_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="">Selecciona un paciente</option>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}">{{ $patient->user->name }}</option>
                    @endforeach
                </select>
                @error('patient_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="doctor_id" class="block mb-2 text-sm font-medium text-gray-900">Doctor <span class="text-red-500">*</span></label>
                <select id="doctor_id" wire:model.live="doctor_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="">Selecciona un doctor</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}">{{ $doctor->user->name }}</option>
                    @endforeach
                </select>
                @error('doctor_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="specialty_id" class="block mb-2 text-sm font-medium text-gray-900">Especialidad (opcional)</label>
                <select id="specialty_id" wire:model="specialty_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="">Selecciona una especialidad</option>
                    @foreach($specialties as $specialty)
                        <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                    @endforeach
                </select>
                @error('specialty_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="status" class="block mb-2 text-sm font-medium text-gray-900">Estado de Cita</label>
                <select id="status" wire:model="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="Programado">Programado</option>
                    <option value="Completado">Completado</option>
                    <option value="Cancelado">Cancelado</option>
                </select>
                @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-xl font-semibold text-gray-900 mb-2">Buscar disponibilidad</h3>
        <p class="text-gray-500 mb-4 text-sm">Actualizar el horario de la cita.</p>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
            <div class="md:col-span-1">
                <label for="date" class="block mb-2 text-sm font-medium text-gray-900">Fecha <span class="text-red-500">*</span></label>
                <input type="date" id="date" wire:model.live="date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                @error('date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="md:col-span-1">
                <label for="start_time" class="block mb-2 text-sm font-medium text-gray-900">Hora <span class="text-red-500">*</span></label>
                <select id="start_time" wire:model="start_time" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" {{ empty($this->availableSlots) ? 'disabled' : '' }}>
                    <option value="">Selecciona una hora</option>
                    @foreach($this->availableSlots as $slot)
                        <option value="{{ $slot }}">{{ $slot }}</option>
                    @endforeach
                </select>
                @if($date && $doctor_id && empty($this->availableSlots))
                    <p class="text-red-500 text-xs mt-1">No hay horarios disponibles.</p>
                @endif
                @error('start_time') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="md:col-span-2 flex justify-end">
                <a href="{{ route('admin.appointments.index') }}" class="mr-3 text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-8 py-2.5 text-center">
                    Cancelar
                </a>
                <button wire:click="save" type="button" class="text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 font-medium rounded-lg text-sm px-8 py-2.5 dark:bg-indigo-600 dark:hover:bg-indigo-700 focus:outline-none dark:focus:ring-indigo-800">
                    Actualizar cita
                </button>
            </div>
        </div>
    </div>
</div>
