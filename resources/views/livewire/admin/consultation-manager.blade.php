<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-semibold text-gray-900">{{ $appointment->patient->user->name }}</h2>
            <p class="text-sm text-gray-500">DNI/ID: {{ $appointment->patient->user->id_number }}</p>
            <p class="text-xs text-gray-400 mt-1">Fecha: {{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }} | Hora: {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.patients.show', $appointment->patient_id) }}" target="_blank" class="text-gray-900 bg-white border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-4 py-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                <i class="fa-solid fa-notes-medical mr-1"></i> Ver Historia
            </a>
            <button type="button" wire:click="$set('showPastConsultationsModal', true)" class="text-gray-900 bg-white border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-4 py-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                <i class="fa-solid fa-clock-rotate-left mr-1"></i> Consultas Anteriores
            </button>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="border-b border-gray-200">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500">
                <li class="mr-2">
                    <button wire:click="setActiveTab('consulta')" class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg group {{ $activeTab === 'consulta' ? 'text-indigo-600 border-indigo-600 active' : 'border-transparent hover:text-gray-600 hover:border-gray-300' }}">
                        <i class="fa-solid fa-stethoscope mr-2 {{ $activeTab === 'consulta' ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                        Consulta
                    </button>
                </li>
                <li class="mr-2">
                    <button wire:click="setActiveTab('receta')" class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg group {{ $activeTab === 'receta' ? 'text-indigo-600 border-indigo-600 active' : 'border-transparent hover:text-gray-600 hover:border-gray-300' }}">
                        <i class="fa-solid fa-prescription-bottle-medical mr-2 {{ $activeTab === 'receta' ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                        Receta
                    </button>
                </li>
            </ul>
        </div>

        <div class="p-6">
            @if($activeTab === 'consulta')
                <div class="space-y-6">
                    <div>
                        <label for="diagnosis" class="block mb-2 text-sm font-medium text-gray-900">Diagnóstico</label>
                        <textarea id="diagnosis" wire:model="diagnosis" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Describa el diagnóstico del paciente aquí..."></textarea>
                        @error('diagnosis') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="treatment" class="block mb-2 text-sm font-medium text-gray-900">Tratamiento</label>
                        <textarea id="treatment" wire:model="treatment" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Describa el tratamiento recomendado aquí..."></textarea>
                        @error('treatment') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="notes" class="block mb-2 text-sm font-medium text-gray-900">Notas Adicionales</label>
                        <textarea id="notes" wire:model="notes" rows="3" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Agregue notas adicionales sobre la consulta..."></textarea>
                        @error('notes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
            @endif

            @if($activeTab === 'receta')
                <div class="space-y-4">
                    @foreach($prescriptionItems as $index => $item)
                        <div class="flex items-start space-x-4 bg-gray-50 p-4 rounded-lg border border-gray-200" wire:key="prescription-item-{{ $index }}">
                            <div class="flex-1">
                                <label class="block mb-1 text-xs font-medium text-gray-700">Medicamento</label>
                                <input type="text" wire:model="prescriptionItems.{{ $index }}.medicine_name" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2" placeholder="Ej: Amoxicilina 500mg">
                                @error('prescriptionItems.'.$index.'.medicine_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="w-1/4">
                                <label class="block mb-1 text-xs font-medium text-gray-700">Dosis</label>
                                <input type="text" wire:model="prescriptionItems.{{ $index }}.dosage" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2" placeholder="Ej: 1 pastilla">
                                @error('prescriptionItems.'.$index.'.dosage') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="w-1/3">
                                <label class="block mb-1 text-xs font-medium text-gray-700">Frecuencia / Duración</label>
                                <input type="text" wire:model="prescriptionItems.{{ $index }}.frequency" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2" placeholder="Ej: cada 8 horas por 7 días">
                            </div>
                            <div class="flex items-center pt-6">
                                <button type="button" wire:click="removePrescriptionItem({{ $index }})" class="text-red-600 hover:text-white border border-red-600 hover:bg-red-600 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-3 py-2 text-center">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach

                    <button type="button" wire:click="addPrescriptionItem" class="mt-2 text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:ring-4 focus:outline-none focus:ring-indigo-300 font-medium rounded-lg text-sm px-4 py-2 text-center inline-flex items-center">
                        <i class="fa-solid fa-plus mr-2"></i> Añadir Medicamento
                    </button>
                </div>
            @endif
        </div>
        
        <div class="p-6 border-t border-gray-200 flex justify-end">
            <button wire:click="saveConsultation" type="button" class="text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 font-medium rounded-lg text-sm px-6 py-2.5 dark:bg-indigo-600 dark:hover:bg-indigo-700 focus:outline-none dark:focus:ring-indigo-800">
                <i class="fa-solid fa-save mr-2"></i> Guardar Consulta
            </button>
        </div>
    </div>

    <!-- Modal Consultas Anteriores -->
    @if($pastAppointments->count() > 0)
        <x-modal wire:model="showPastConsultationsModal" maxWidth="2xl">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                        Consultas Anteriores - {{ $appointment->patient->user->name }}
                    </h3>
                    <button type="button" x-on:click="$dispatch('close')" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
                
                <div class="space-y-4 max-h-[70vh] overflow-y-auto pr-2">
                    @foreach($pastAppointments as $past)
                        <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                            <div class="flex justify-between items-center mb-2">
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                                    {{ \Carbon\Carbon::parse($past->date)->format('d M Y') }}
                                </span>
                                <span class="text-sm text-gray-500 dark:text-gray-400">Dr. {{ optional($past->doctor->user)->name }}</span>
                            </div>
                            
                            <div class="mb-4">
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Motivo</h4>
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $past->notes ?: 'No especificado' }}</p>
                            </div>

                            @if($past->diagnosis)
                                <div class="mb-4">
                                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Diagnóstico</h4>
                                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ $past->diagnosis }}</p>
                                </div>
                            @endif

                            @if($past->treatment)
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Tratamiento</h4>
                                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ $past->treatment }}</p>
                                </div>
                            @endif
                            
                            @if($past->prescriptionItems->count() > 0)
                                <div class="mt-4 border-t pt-3">
                                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-2"><i class="fa-solid fa-prescription fa-sm mr-1"></i> Receta</h4>
                                    <ul class="list-disc pl-5 text-sm text-gray-700 dark:text-gray-300">
                                        @foreach($past->prescriptionItems as $med)
                                            <li>{{ $med->medicine_name }} - {{ $med->dosage }} ({{ $med->frequency }})</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </x-modal>
    @else
        <x-modal wire:model="showPastConsultationsModal">
            <div class="p-6 text-center">
                <i class="fa-regular fa-folder-open text-gray-400 text-5xl mb-4"></i>
                <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">No hay consultas anteriores registradas para este paciente.</h3>
                <button type="button" x-on:click="$dispatch('close')" class="text-white bg-indigo-600 hover:bg-indigo-800 focus:ring-4 focus:outline-none focus:ring-indigo-300 dark:focus:ring-indigo-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                    Cerrar
                </button>
            </div>
        </x-modal>
    @endif
</div>
