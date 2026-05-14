<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // USUARIO ADMIN
        User::updateOrCreate(
            ['email' => 'admin@example.com'], // clave única
            [
                'name' => 'Administrador',
                'password' => 'Admin1234!', // El mutador de User se encargará de hashear la contraseña
                'role' => 'admin',
            ]
        );

        $this->command->info('Usuario administrador creado o actualizado correctamente.');
        $this->command->info('Email: admin@example.com');
        $this->command->info('Password: Admin1234!');
    }
}
