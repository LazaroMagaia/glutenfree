<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     public function run(): void
    {
        $users = [
            ['name' => 'Administrador', 'email' => 'admin@example.com',  'phone' => '+258841000001', 'role' => UserRole::Admin],
            ['name' => 'Ana Machava',   'email' => 'ana@example.com',    'phone' => '+258842000002', 'role' => UserRole::User],
            ['name' => 'Carlos Sitoe',  'email' => 'carlos@example.com', 'phone' => '+258843000003', 'role' => UserRole::User],
        ];

        foreach ($users as $data) {
            User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name'              => $data['name'],
                    'phone'             => $data['phone'],
                    'role'              => $data['role'],
                    'password'          => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );
        }

        // Utilizadores extra (role = user por defeito)
        User::factory()->count(10)->create();
    }
}
