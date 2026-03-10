<x-admin-layout title="Citas | MediMatch" :breadcrumbs="[
        [
            'name' => 'Dashboard',
            'href' => route('admin.dashboard'),
        ],
        [
            'name' => 'Citas',
        ],
    ]">

    <x-slot name="action">
        <a href="{{ route('admin.appointments.create') }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
            + Nuevo
        </a>
    </x-slot>

    <div class="bg-white rounded-lg shadow p-6">
        <livewire:admin.data-tables.appointment-table />
    </div>

</x-admin-layout>
