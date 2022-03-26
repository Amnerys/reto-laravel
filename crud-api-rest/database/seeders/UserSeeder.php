<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     *
     */
    public function run()
    {
        // Primero crearemos un usuario admin:
        $user = new User();
        $user->nombre = 'admin';
        $user->apellidos = 'nistrador';
        $user->fecha_nacimiento = '2000-01-01';
        $user->email = 'admin@admin.com';
        $user->password = 'admin';
        $user->foto = 'foto.jpg';
        $user->save();

        // Después usaremos el UserFactory para crear usuarios autogenerados (3 en total)
        User::factory()->times(3)->create();
    }
}
