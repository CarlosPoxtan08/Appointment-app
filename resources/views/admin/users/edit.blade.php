<x-admin-layout
    title="Editar Usuario | MediMatch"
    :breadcrumbs="[
        [
            'name' => 'Dashboard',
            'href' => route('admin.dashboard'),
        ],
        [
            'name' => 'Usuarios',
            'href' => route('admin.users.index'),
        ],
        [
            'name' => 'Editar',
        ],
    ]">

    <x-wire-card>
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid lg:grid-cols-2 gap-4">
                <x-wire-input label="Nombre" name="name" placeholder="Nombre del usuario"
                    value="{{ old('name', $user->name) }}" autocomplete="name">
                </x-wire-input>

                <x-wire-input label="Email" name="email" type="email" placeholder="correo@ejemplo.com"
                    value="{{ old('email', $user->email) }}" inputmode="email" autocomplete="email">
                </x-wire-input>

                <x-wire-input label="Contraseña" name="password" type="password" placeholder="Dejar vacío para mantener la actual"
                    autocomplete="new-password">
                </x-wire-input>

                <x-wire-input label="Confirmar Contraseña" name="password_confirmation" type="password" placeholder="Confirme la contraseña"
                    autocomplete="new-password">
                </x-wire-input>

                <x-wire-input label="Número de identificación" name="id_number" placeholder="Número de identificación"
                    value="{{ old('id_number', $user->id_number) }}">
                </x-wire-input>

                <x-wire-input label="Teléfono" name="phone" placeholder="Número de teléfono"
                    value="{{ old('phone', $user->phone) }}" inputmode="tel" autocomplete="phone">
                </x-wire-input>

                <x-wire-input label="Dirección" name="address" placeholder="Dirección completa"
                    class="lg:col-span-2" value="{{ old('address', $user->address) }}">
                </x-wire-input>

                <div class="space-y-1">
                    <x-wire-native-select label="Rol" name="role" :options="$roles"
                        placeholder="Seleccione un rol" :value="old('role', $user->roles->first()?->name)">
                    </x-wire-native-select>
                    <p class="text-xs text-gray-500">Define los permisos y accesos del usuario</p>
                </div>
            </div>

            <div class="flex justify-end mt-4">
                <x-wire-button type="submit" blue>Actualizar</x-wire-button>
            </div>

        </form>
    </x-wire-card>

    <div class="flex justify-end mt-4">
        <x-wire-button flat secondary href="{{ route('admin.users.index') }}">
            Volver a la lista de usuarios
        </x-wire-button>
    </div>

</x-admin-layout>
