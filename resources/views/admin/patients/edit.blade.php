<x-admin-layout
    title="Editar Paciente | MediMatch"
    :breadcrumbs="[
        [
            'name' => 'Dashboard',
            'href' => route('admin.dashboard'),
        ],
        [
            'name' => 'Pacientes',
            'href' => route('admin.patients.index'),
        ],
        [
            'name' => 'Editar',
        ],
    ]"
>
    {{-- Header Card --}}
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-8 mb-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-5">
                <div class="flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 text-blue-600">
                    <i class="fa-solid fa-user-injured text-3xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $patient->user->name }}</h2>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.patients.index') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-4 focus:ring-gray-200">
                    <i class="fa-solid fa-arrow-left"></i>
                    Volver
                </a>
                <button type="submit" form="edit-patient-form"
                        class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
                    <i class="fa-solid fa-check"></i>
                    Guardar cambios
                </button>
            </div>
        </div>
    </div>

    {{-- Navegación de pestañas --}}
    <div class="mb-4 border-b border-gray-200">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="patientTabs" data-tabs-toggle="#patientTabContent" role="tablist">
            <li class="me-2" role="presentation">
                <button class="inline-flex items-center gap-2 p-4 border-b-2 rounded-t-lg"
                        id="personal-tab" data-tabs-target="#personal" type="button" role="tab"
                        aria-controls="personal" aria-selected="true">
                    <i class="fa-solid fa-id-card"></i>
                    Datos Personales
                </button>
            </li>
            <li class="me-2" role="presentation">
                <button class="inline-flex items-center gap-2 p-4 border-b-2 rounded-t-lg"
                        id="history-tab" data-tabs-target="#history" type="button" role="tab"
                        aria-controls="history" aria-selected="false">
                    <i class="fa-solid fa-notes-medical"></i>
                    Antecedentes
                    <span id="history-error-badge" class="hidden w-2 h-2 rounded-full bg-red-500"></span>
                </button>
            </li>
            <li class="me-2" role="presentation">
                <button class="inline-flex items-center gap-2 p-4 border-b-2 rounded-t-lg"
                        id="general-tab" data-tabs-target="#general" type="button" role="tab"
                        aria-controls="general" aria-selected="false">
                    <i class="fa-solid fa-circle-info"></i>
                    Información General
                    <span id="general-error-badge" class="hidden w-2 h-2 rounded-full bg-red-500"></span>
                </button>
            </li>
            <li role="presentation">
                <button class="inline-flex items-center gap-2 p-4 border-b-2 rounded-t-lg"
                        id="emergency-tab" data-tabs-target="#emergency" type="button" role="tab"
                        aria-controls="emergency" aria-selected="false">
                    <i class="fa-solid fa-phone-volume"></i>
                    Contacto de Emergencia
                    <span id="emergency-error-badge" class="hidden w-2 h-2 rounded-full bg-red-500"></span>
                </button>
            </li>
        </ul>
    </div>

    {{-- Formulario que envuelve todas las pestañas --}}
    <form id="edit-patient-form" action="{{ route('admin.patients.update', $patient) }}" method="POST">
        @csrf
        @method('PUT')

        <div id="patientTabContent">
            {{-- Pestaña 1: Datos Personales (solo lectura) --}}
            <div class="hidden p-6 bg-white border border-gray-200 rounded-lg shadow-sm" id="personal" role="tabpanel" aria-labelledby="personal-tab">
                {{-- Aviso de edición de usuario --}}
                <x-wire-alert class="mb-6" color="info" title="Edición de cuenta de usuario">
                    <strong>La información de acceso</strong> (Nombre, Email y Contraseña) debe gestionarse desde la cuenta del usuario asociada.
                    <x-slot name="action">
                        <x-wire-button blue sm href="{{ route('admin.users.edit', $patient->user) }}">
                            <i class="fa-solid fa-user-pen"></i>
                            Editar Usuario
                        </x-wire-button>
                    </x-slot>
                </x-wire-alert>

                <div class="grid lg:grid-cols-2 gap-x-8 gap-y-5">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Email</p>
                        <p class="mt-1 text-base text-gray-900">{{ $patient->user->email ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-500">Teléfono</p>
                        <p class="mt-1 text-base text-gray-900">{{ $patient->user->phone ?? '-' }}</p>
                    </div>

                    <div class="lg:col-span-2">
                        <p class="text-sm font-medium text-gray-500">Dirección</p>
                        <p class="mt-1 text-base text-gray-900">{{ $patient->user->address ?? '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- Pestaña 2: Antecedentes --}}
            <div class="hidden p-6 bg-white border border-gray-200 rounded-lg shadow-sm" id="history" role="tabpanel" aria-labelledby="history-tab">
                <div class="grid lg:grid-cols-2 gap-4">
                    <div class="space-y-1 lg:col-span-2 lg:w-1/2">
                        <x-wire-native-select label="Tipo de sangre" name="blood_type_id" :options="$bloodTypes"
                            placeholder="Seleccione tipo de sangre" :value="old('blood_type_id', $patient->blood_type_id)">
                        </x-wire-native-select>
                    </div>

                    <x-wire-textarea label="Alergias" name="allergies" placeholder="Alergias conocidas (opcional)"
                        :value="old('allergies', $patient->allergies)">
                    </x-wire-textarea>

                    <x-wire-textarea label="Condiciones crónicas" name="chronic_conditions" placeholder="Condiciones crónicas (opcional)"
                        :value="old('chronic_conditions', $patient->chronic_conditions)">
                    </x-wire-textarea>

                    <x-wire-textarea label="Historial quirúrgico" name="surgical_history" placeholder="Cirugías previas (opcional)"
                        class="lg:col-span-2" :value="old('surgical_history', $patient->surgical_history)">
                    </x-wire-textarea>
                </div>
            </div>

            {{-- Pestaña 3: Información General --}}
            <div class="hidden p-6 bg-white border border-gray-200 rounded-lg shadow-sm" id="general" role="tabpanel" aria-labelledby="general-tab">
                <div class="grid lg:grid-cols-2 gap-4">
                    <x-wire-input label="Fecha de nacimiento" name="date_of_birth" type="date"
                        value="{{ old('date_of_birth', $patient->date_of_birth->format('Y-m-d')) }}">
                    </x-wire-input>

                    <div class="space-y-1">
                        <x-wire-native-select label="Género" name="gender" :options="[
                            'male' => 'Masculino',
                            'female' => 'Femenino',
                            'other' => 'Otro',
                        ]" placeholder="Seleccione género" :value="old('gender', $patient->gender)">
                        </x-wire-native-select>
                    </div>

                    <x-wire-input label="Número de identificación" name="id_number" placeholder="Número de identificación"
                        value="{{ old('id_number', $patient->user->id_number) }}">
                    </x-wire-input>

                    <x-wire-textarea label="Observaciones" name="observations" placeholder="Observaciones adicionales (opcional)"
                        class="lg:col-span-2" :value="old('observations', $patient->observations)">
                    </x-wire-textarea>
                </div>
            </div>

            {{-- Pestaña 4: Contacto de Emergencia --}}
            <div class="hidden p-6 bg-white border border-gray-200 rounded-lg shadow-sm" id="emergency" role="tabpanel" aria-labelledby="emergency-tab">
                <div class="grid lg:grid-cols-2 gap-4">
                    <x-wire-input label="Contacto de emergencia" name="emergency_contact_name" placeholder="Nombre del contacto"
                        value="{{ old('emergency_contact_name', $patient->emergency_contact_name) }}">
                    </x-wire-input>

                    <x-wire-input label="Teléfono de emergencia" name="emergency_contact_phone" placeholder="Teléfono del contacto"
                        value="{{ old('emergency_contact_phone', $patient->emergency_contact_phone) }}" inputmode="tel">
                    </x-wire-input>

                    <x-wire-input label="Parentesco" name="emergency_relationship" placeholder="Parentesco con el paciente"
                        value="{{ old('emergency_relationship', $patient->emergency_relationship) }}">
                    </x-wire-input>
                </div>
            </div>
        </div>
    </form>

    {{-- Script para detectar errores por pestaña y mostrar indicadores --}}
    @if($errors->any())
    @php
        $tabErrors = [
            'history' => $errors->hasAny(['blood_type_id', 'allergies', 'chronic_conditions', 'surgical_history']),
            'general' => $errors->hasAny(['date_of_birth', 'gender', 'id_number', 'observations']),
            'emergency' => $errors->hasAny(['emergency_contact_name', 'emergency_contact_phone', 'emergency_relationship']),
        ];
    @endphp
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabErrors = @json($tabErrors);

            let firstErrorTab = null;

            for (const [tab, hasError] of Object.entries(tabErrors)) {
                if (hasError) {
                    const badge = document.getElementById(tab + '-error-badge');
                    if (badge) badge.classList.remove('hidden');
                    if (!firstErrorTab) firstErrorTab = tab;
                }
            }

            // Mostrar la primera pestaña con errores
            if (firstErrorTab) {
                const tabButton = document.getElementById(firstErrorTab + '-tab');
                if (tabButton) {
                    setTimeout(function() {
                        tabButton.click();
                    }, 100);
                }
            }
        });
    </script>
    @endif

</x-admin-layout>
