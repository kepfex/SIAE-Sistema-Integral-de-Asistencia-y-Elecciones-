<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'Super Usuario',
            'email' => 'admin@aureliocardenas.com',
        ]);

        // Llamar a otros seeders
        $this->call([
            AnioEscolarSeeder::class,
            GradosSeeder::class,
            SeccionesSeeder::class,
            RoleSeeder::class,
            HorarioSeeder::class,
        ]);

        $admin->roles()->attach(Role::ADMIN);
    }
}
