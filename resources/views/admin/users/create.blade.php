<x-admin-layout
    title="Usuarios | MediMatch"
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
            'name' => 'Nuevo',
        ],
    ]"
>
    <x-wire-card>
        <form action="{{route('admin.users.store')}}" method="POST">
            @csrf

            <x-wire-input label="Nombre" name="name" placeholder="Nombre del usuario"
            value="{{old('name')}}" autocomplete="name">
            </x-wire-input>

            <x-wire-input label="Email" name="email" type="email" placeholder="correo@ejemplo.com"
            value="{{old('email')}}" inputmode="email" autocomplete="email">
            </x-wire-input>

            <x-wire-input label="Número de identificación" name="id_number" placeholder="Número de identificación"
            value="{{old('id_number')}}" autocomplete="id_number">
            </x-wire-input>
            <x-wire-input label="Teléfono" name="phone" placeholder="Número de teléfono"
            value="{{old('phone')}}" inputmode="tel" autocomplete="phone">
            </x-wire-input>

            <x-wire-input label="Contraseña" name="password" type="password" placeholder="Ingrese la contraseña"
            autocomplete="new-password">
            </x-wire-input>

            <x-wire-input label="Confirmar Contraseña" name="password_confirmation" type="password" placeholder="Confirme la contraseña"
            autocomplete="new-password">
            </x-wire-input>

            <x-wire-textarea label="Dirección" name="address" placeholder="Dirección completa"
            :value="old('address')">
            </x-wire-textarea>

            <div class="space-y-1">
                <x-wire-native-select label="Rol" name="role" :options="$roles"
                placeholder="Seleccione un rol" :value="old('role')">
                </x-wire-native-select>
            </div>

            <div class="flex justify-end mt-4">
                <x-wire-button type="submit" blue>Guardar</x-wire-button>
            </div>

        </form>
    </x-wire-card>

    <div class="flex justify-end mt-4">
        <x-wire-button flat secondary href="{{route('admin.users.index')}}">
            Volver a la lista de usuarios
        </x-wire-button>
    </div>

</x-admin-layout>
