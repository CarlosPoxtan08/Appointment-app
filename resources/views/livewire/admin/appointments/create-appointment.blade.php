<div>
    <!-- Top Search Header -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-xl font-semibold text-gray-900 mb-1">Buscar disponibilidad</h3>
        <p class="text-sm text-gray-500 mb-4">Encuentra el horario perfecto para tu cita.</p>
        
        <div class="flex flex-col md:flex-row md:items-end gap-6">
            <div class="flex-1">
                <label for="searchDate" class="block mb-2 text-sm font-medium text-gray-900">Fecha</label>
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                        <i class="fa-regular fa-calendar text-gray-500"></i>
                    </div>
                    <input type="date" wire:model="searchDate" id="searchDate" min="{{ \Carbon\Carbon::today()->format('Y-m-d') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full ps-10 p-2.5">
                </div>
                @error('searchDate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="flex-1">
                <label for="searchTimeRange" class="block mb-2 text-sm font-medium text-gray-900">Hora</label>
                <select id="searchTimeRange" wire:model="searchTimeRange" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 outline-none appearance-none">
                    <option value="">Cualquier horario</option>
                    <option value="06:00-09:00">06:00 - 09:00</option>
                    <option value="09:00-12:00">09:00 - 12:00</option>
                    <option value="12:00-15:00">12:00 - 15:00</option>
                    <option value="15:00-18:00">15:00 - 18:00</option>
                    <option value="18:00-21:00">18:00 - 21:00</option>
                </select>
            </div>

            <div class="flex-1">
                <label for="searchSpecialty" class="block mb-2 text-sm font-medium text-gray-900">Especialidad (opcional)</label>
                <select id="searchSpecialty" wire:model="searchSpecialty" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 outline-none appearance-none">
                    <option value="">Todas las especialidades</option>
                    @foreach($specialties as $specialty)
                        <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <button wire:click="searchAvailability" type="button" class="w-full md:w-auto text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 font-medium rounded-lg text-sm px-6 py-2.5 mb-0.5">
                    Buscar disponibilidad
                </button>
            </div>
        </div>
    </div>

    <!-- Main Content Area / Two Columns -->
    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Left Column: Available Doctors -->
        <div class="w-full lg:w-2/3 space-y-4">
            @if(count($availableDoctors) > 0)
                @foreach($availableDoctors as $doctor)
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-lg mr-4">
                                {{ $doctor['initials'] }}
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900">{{ $doctor['name'] }}</h4>
                                <p class="text-sm text-indigo-600">{{ $doctor['specialty'] ?? 'Médico General' }}</p>
                            </div>
                        </div>

                        <p class="text-sm font-medium text-gray-900 mb-3">Horarios disponibles:</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($doctor['slots'] as $slot)
                                <button type="button" 
                                        wire:click="selectSlot({{ $doctor['id'] }}, '{{ $doctor['name'] }}', '{{ $slot }}')"
                                        class="px-4 py-2 text-sm font-medium rounded-lg transition-colors 
                                        {{ $selectedDoctorId === $doctor['id'] && $selectedTime === $slot 
                                            ? 'bg-indigo-600 text-white hover:bg-indigo-700' 
                                            : 'bg-indigo-100 text-indigo-700 hover:bg-indigo-200' }}">
                                    {{ $slot }}:00
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @else
                <div class="bg-white rounded-lg shadow p-6 text-center text-gray-500">
                    <p>Realiza una búsqueda o intenta con otra fecha/hora para ver la disponibilidad.</p>
                </div>
            @endif
        </div>

        <!-- Right Column: Summary Panel -->
        <div class="w-full lg:w-1/3">
            <div class="bg-white rounded-lg shadow p-6 sticky top-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Resumen de la cita</h3>

                @error('selectedDoctorId') <div class="p-3 mb-4 text-sm text-red-800 rounded-lg bg-red-50"><i class="fa-solid fa-circle-exclamation mr-1"></i> {{ $message }}</div> @enderror

                <div class="space-y-3 mb-6 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Doctor:</span>
                        <span class="font-medium text-gray-900">{{ $selectedDoctorName ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Fecha:</span>
                        <span class="font-medium text-gray-900">{{ $searchDate ? \Carbon\Carbon::parse($searchDate)->format('Y-m-d') : '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Horario:</span>
                        <span class="font-medium text-gray-900">
                            @if($selectedTime)
                                {{ $selectedTime }}:00 - {{ \Carbon\Carbon::parse($selectedTime)->addMinutes(15)->format('H:i') }}:00
                            @else
                                -
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Duración:</span>
                        <span class="font-medium text-gray-900">15 minutos</span>
                    </div>
                </div>

                <div class="mb-4 text-sm">
                    <label for="patient_id" class="block mb-2 text-sm font-medium text-gray-900">Paciente</label>
                    <select id="patient_id" wire:model="patient_id" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 outline-none appearance-none">
                        <option value="">Seleccione un paciente</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}">{{ $patient->user->name }}</option>
                        @endforeach
                    </select>
                    @error('patient_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mb-6">
                    <label for="notes" class="block mb-2 text-sm font-medium text-gray-900">Motivo de la cita</label>
                    <textarea id="notes" wire:model="notes" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-white rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Ej: Chequeo de medicamentos..."></textarea>
                    @error('notes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <button wire:click="save" type="button" class="w-full text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 font-medium rounded-lg text-sm px-5 py-3 text-center">
                    Confirmar cita
                </button>
            </div>
        </div>
    </div>
</div>
