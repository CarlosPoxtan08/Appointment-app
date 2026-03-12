<x-admin-layout title="Horarios de Doctor | MediMatch" :breadcrumbs="[
        [
            'name' => 'Dashboard',
            'href' => route('admin.dashboard'),
        ],
        [
            'name' => 'Doctores',
            'href' => route('admin.doctors.index'),
        ],
        [
            'name' => 'Horarios',
        ],
    ]">

    <div class="mb-4">
        <h2 class="text-2xl font-semibold text-gray-900">Horarios de {{ $doctor->user->name }}</h2>
        <p class="text-sm text-gray-500">{{ optional($doctor->specialty)->name }}</p>
    </div>

    <livewire:admin.doctors.schedule-form :doctor="$doctor" />

</x-admin-layout>
