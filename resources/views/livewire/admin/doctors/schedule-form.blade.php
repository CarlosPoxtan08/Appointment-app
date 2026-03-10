<div class="mt-6">
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold text-gray-900">Horarios de Disponibilidad</h3>
            <button wire:click="addSchedule" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                <i class="fa-solid fa-plus mr-1"></i> Agregar Día
            </button>
        </div>

        <div class="space-y-4">
            @foreach($schedules as $index => $schedule)
                <div class="flex flex-col sm:flex-row gap-4 items-end bg-gray-50 p-4 rounded-lg border border-gray-200" wire:key="schedule-{{ $index }}">
                    <div class="flex-1 w-full">
                        <label class="block mb-2 text-sm font-medium text-gray-900">Día de la semana</label>
                        <select wire:model="schedules.{{ $index }}.day_of_week" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            @foreach($days as $val => $label)
                                <option value="{{ $val }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('schedules.'.$index.'.day_of_week') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex-1 w-full">
                        <label class="block mb-2 text-sm font-medium text-gray-900">Hora de inicio</label>
                        <input type="time" wire:model="schedules.{{ $index }}.start_time" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        @error('schedules.'.$index.'.start_time') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex-1 w-full">
                        <label class="block mb-2 text-sm font-medium text-gray-900">Hora de fin</label>
                        <input type="time" wire:model="schedules.{{ $index }}.end_time" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        @error('schedules.'.$index.'.end_time') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <button wire:click="removeSchedule({{ $index }})" type="button" class="text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-4 py-2.5 text-center mb-0">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </div>
            @endforeach

            @if(count($schedules) === 0)
                <p class="text-sm text-gray-500 text-center py-4">No hay horarios definidos para este doctor.</p>
            @endif
        </div>

        <div class="mt-6 flex justify-end">
            <button wire:click="save" type="button" class="text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-6 py-2.5">
                <i class="fa-solid fa-save mr-1"></i> Guardar Horarios
            </button>
        </div>
    </div>
    
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('swal-trigger', () => {
                if(window.Swal) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: 'Horario actualizado correctamente.',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
            });
        });
    </script>
</div>
