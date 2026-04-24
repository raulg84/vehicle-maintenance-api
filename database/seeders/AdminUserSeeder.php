<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
    }
}
