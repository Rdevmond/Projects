<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@hub.com'],
            [
                'name' => 'HUB Administrator',
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'plain_password' => Crypt::encryptString('admin123'),
                'role' => 1,
                'school' => 'HUB Headquarters',
            ]
        );
    }
}
