<x-admin-layout title="Editar Doctor | MediMatch" :breadcrumbs="[
        [
            'name' => 'Dashboard',
            'href' => route('admin.dashboard'),
        ],
        [
            'name' => 'Doctores',
            'href' => route('admin.doctors.index'),
        ],
        [
            'name' => 'Editar',
        ],
    ]">

    <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-8 mb-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-5">
                <div class="flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 text-blue-600">
                    <i class="fa-solid fa-user-doctor text-3xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $doctor->user->name }}</h2>
                    <p class="text-sm text-gray-500">Cédula: {{ $doctor->licencia ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-500">Biografía: {{ $doctor->biografia ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.doctors.index') }}"
                    class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    <i class="fa-solid fa-arrow-left"></i>
                    Volver
                </a>
                <button type="submit" form="edit-doctor-form"
                    class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    <i class="fa-solid fa-check"></i>
                    Guardar cambios
                </button>
            </div>
        </div>
    </div>

    <form id="edit-doctor-form" action="{{ route('admin.doctors.update', $doctor->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
            <div class="grid grid-cols-1 gap-4">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Especialidad</label>
                    <select name="specialty_id"
                        class="w-full border @error('specialty_id') border-red-500 @else border-gray-300 @enderror rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach($specialties as $specialty)
                            <option value="{{ $specialty->id }}" {{ old('specialty_id', $doctor->specialty_id) == $specialty->id ? 'selected' : '' }}>
                                {{ $specialty->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('specialty_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cédula Profesional</label>
                    <input type="text" name="license" value="{{ old('license', $doctor->license) }}"
                        placeholder="Ingresa la cédula profesional"
                        class="w-full border @error('license') border-red-500 @else border-gray-300 @enderror rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('license')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Biografía</label>
                    <textarea name="biography" rows="4" placeholder="Ingresa la biografía del doctor"
                        class="w-full border @error('biography') border-red-500 @else border-gray-300 @enderror rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('biography', $doctor->biography) }}</textarea>
                    @error('biography')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>
        </div>
    </form>

</x-admin-layout>