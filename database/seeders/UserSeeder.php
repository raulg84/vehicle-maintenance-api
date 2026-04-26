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
            ['email' => 'admin@tfm.local'], // clave única
            [
                'name' => 'Administrador',
                'password' => 'Admin1234!', // El mutador de User se encargará de hashear la contraseña
                'role' => 'admin',
            ]
        );

        $this->command->info('Usuario administrador creado o actualizado correctamente.');
        $this->command->info('Email: admin@tfm.local');
        $this->command->info('Password: Admin1234!');

         // USER NORMAL
        User::updateOrCreate(
            ['email' => 'user@tfm.local'],
            [
                'name' => 'Usuario Demo',
                'password' => 'User1234!',
                'role' => 'user',
            ]
        );

        $this->command->info('Usuario normal creado o actualizado correctamente.');
        $this->command->info('Email: user@tfm.local');
        $this->command->info('Password: User1234!');
    }
}
