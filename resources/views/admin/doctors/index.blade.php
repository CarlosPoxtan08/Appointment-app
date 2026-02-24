<x-admin-layout title="Doctores | MediMatch" :breadcrumbs="[
        [
            'name' => 'Dashboard',
            'href' => route('admin.dashboard'),
        ],
        [
            'name' => 'Doctores',
        ],
    ]">

    <div class="bg-white rounded-lg shadow p-6">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-600 uppercase">
                <tr>
                    <th class="px-4 py-2">Nombre</th>
                    <th class="px-4 py-2">Especialidad</th>
                    <th class="px-4 py-2">Cédula Profesional</th>
                    <th class="px-4 py-2">Biografía</th>
                    <th class="px-4 py-2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($doctors as $doctor)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $doctor->user->name }}</td>
                        <td class="px-4 py-2">{{ $doctor->specialty->name }}</td>
                        <td class="px-4 py-2">{{ $doctor->license ?? 'N/A' }}</td>
                        <td class="px-4 py-2">{{ $doctor->biography ?? 'N/A' }}</td>
                        <td class="px-4 py-2">
                            <a href="{{ route('admin.doctors.edit', $doctor->id) }}"
                                class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                Editar
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</x-admin-layout>