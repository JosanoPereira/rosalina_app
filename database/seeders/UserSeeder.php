<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dados = [
            [
                'name' => 'admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make('admin'),
                'remember_token' => null,
            ],
            [
                'name' => 'user',
                'email' => 'user@user.com',
                'password' => bcrypt('user'),
                'remember_token' => null,
            ],
        ];

        foreach ($dados as $key => $dado) {
            $us = User::all()->where('email', $dado['email'])->first();

            if (!$us) {
                User::create($dado);
            }
        }
        $userRole = User::where('email', 'admin@admin.com')->first();
        if ($userRole) {
            $userRole->assignRole('Admin');
        }
    }
}
