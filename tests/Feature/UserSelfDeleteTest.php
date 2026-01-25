<?php
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

/**
 * TEST 1: VALIDACIÓN DELETE - Un usuario no puede eliminarse a sí mismo
 *
 * Regla de negocio: Por seguridad, el sistema debe impedir que un usuario
 * elimine su propia cuenta desde el panel de administración.
 */
test('un usuario no puede eliminarse a si mismo', function () {

    // CREAR USUARIO DE PRUEBA
    $user = User::factory()->create();

    // Simulamos que ya inició sesión
    $this->actingAs($user, 'web');

    // Simular que el usuario intenta eliminarse a sí mismo
    $response = $this->delete(route('admin.users.destroy', $user->id));

    // Esperamos un error 403 (Forbidden)
    $response->assertStatus(403);

    // Verificamos que el usuario sigue existiendo en la base de datos
    $this->assertDatabaseHas('users', ['id' => $user->id]);

});

/**
 * TEST 2: VALIDACIÓN CREATE - El email debe ser único
 *
 * Regla de negocio: No pueden existir dos usuarios con el mismo email
 * en el sistema. El backend debe rechazar emails duplicados.
 */
test('no se puede crear un usuario con email duplicado', function () {

    // CREAR EL ROL NECESARIO
    Role::create(['name' => 'Paciente']);

    // CREAR UN USUARIO CON EMAIL ESPECÍFICO
    $existingUser = User::factory()->create([
        'email' => 'correo@ejemplo.com',
    ]);

    // CREAR ADMIN PARA AUTENTICACIÓN
    $admin = User::factory()->create();
    $this->actingAs($admin, 'web');

    // Intentamos crear otro usuario con el MISMO email
    $userData = [
        'name' => 'Nuevo Usuario',
        'email' => 'correo@ejemplo.com', // EMAIL DUPLICADO
        'id_number' => '99999999',
        'phone' => '9999999999',
        'address' => 'Dirección de prueba',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role' => 'Paciente',
    ];

    // Enviamos la petición POST
    $response = $this->post(route('admin.users.store'), $userData);

    // Esperamos error de validación en el campo email
    $response->assertSessionHasErrors('email');

    // Verificamos que solo existen 2 usuarios (existingUser + admin)
    $this->assertDatabaseCount('users', 2);

});

/**
 * TEST 3: VALIDACIÓN CREATE - La contraseña debe tener mínimo 8 caracteres
 *
 * Regla de negocio: Por seguridad, las contraseñas deben cumplir
 * con un mínimo de 8 caracteres.
 */
test('la contraseña debe tener minimo 8 caracteres', function () {

    // CREAR EL ROL NECESARIO
    Role::create(['name' => 'Paciente']);

    // CREAR ADMIN PARA AUTENTICACIÓN
    $admin = User::factory()->create();
    $this->actingAs($admin, 'web');

    // Intentamos crear un usuario con contraseña muy corta
    $userData = [
        'name' => 'Usuario Test',
        'email' => 'test@ejemplo.com',
        'id_number' => '12345678',
        'phone' => '9991234567',
        'address' => 'Dirección de prueba',
        'password' => '1234567', // SOLO 7 CARACTERES
        'password_confirmation' => '1234567',
        'role' => 'Paciente',
    ];

    // Enviamos la petición POST
    $response = $this->post(route('admin.users.store'), $userData);

    // Esperamos error de validación en el campo password
    $response->assertSessionHasErrors('password');

    // Verificamos que NO se creó el usuario
    $this->assertDatabaseMissing('users', ['email' => 'test@ejemplo.com']);

});
