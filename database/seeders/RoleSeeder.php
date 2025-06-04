<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'Admin',
            'IT',
            'Motorista',
            'Operador',
            'Passageiro',
        ];

        foreach ($roles as $role) {
            $rol = Role::all()->where('name', $role);

            if ($rol->isEmpty()) {
                Role::create(['name' => $role]);
            }
        }
    }
}
