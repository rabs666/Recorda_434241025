<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Admin Recorda', 'email' => 'admin@recorda.com', 'role' => 'admin', 'status' => 'aktif'],
            ['name' => 'User Recorda', 'email' => 'user@recorda.com', 'role' => 'user', 'status' => 'aktif'],
            ['name' => 'Andy Rafaela', 'email' => 'andy@email.com', 'role' => 'user', 'status' => 'aktif'],
            ['name' => 'Budi Santoso', 'email' => 'budi@email.com', 'role' => 'user', 'status' => 'aktif'],
            ['name' => 'Sari Dewi', 'email' => 'sari@email.com', 'role' => 'admin', 'status' => 'aktif'],
            ['name' => 'Cahyo Pratama', 'email' => 'cahyo@email.com', 'role' => 'user', 'status' => 'nonaktif'],
            ['name' => 'Rina Lestari', 'email' => 'rina@email.com', 'role' => 'user', 'status' => 'aktif'],
            ['name' => 'Dodi Wijaya', 'email' => 'dodi@email.com', 'role' => 'user', 'status' => 'aktif'],
        ];

        foreach ($users as $user) {
            // firstOrCreate: tidak menimpa role/status pengguna yang sudah diubah admin.
            User::firstOrCreate(
                ['email' => $user['email']],
                array_merge($user, ['password' => Hash::make('recorda123')])
            );
        }
    }
}
