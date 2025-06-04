<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userRole = User::where('email', 'josanopereira1999@hotmail.com')->first();
        if ($userRole) {
            $userRole->assignRole('Admin');
        }
    }
}
