<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTesting extends Seeder
{
    public function run(): void
    {
        // 1. Admin Account (If you haven't created one yet)
        User::updateOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Admin Cisco',
                'password' => Hash::make('password'),
                'role' => 1, // Admin
            ]
        );

        // 2. High School Competition Students
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@smk.sch.id',
            'password' => Hash::make('password'),
            'role' => 0, // Student
        ]);

        User::create([
            'name' => 'Siti Aminah',
            'email' => 'siti@highschool.id',
            'password' => Hash::make('password'),
            'role' => 0,
        ]);

        // 3. College Internal Students
        User::create([
            'name' => 'Andi Wijaya',
            'email' => 'andi@mahasiswa.pcr.ac.id',
            'password' => Hash::make('password'),
            'role' => 0,
        ]);

        User::create([
            'name' => 'Rina Putri',
            'email' => 'rina@mahasiswa.pcr.ac.id',
            'password' => Hash::make('password'),
            'role' => 0,
        ]);
    }
}
