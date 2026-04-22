<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class UsersImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    public function model(array $row)
    {
        // Headers are mapped to lower case arrays if WithHeadingRow is used.
        $name     = $row['name'] ?? null;
        $username = $row['username'] ?? null;
        $email    = $row['email'] ?? null;
        
        // Skip row if essential data is missing
        if (!$name || !$username || !$email) {
            return null;
        }

        // Skip if user already exists based on username or email
        if (User::where('username', $username)->orWhere('email', $email)->exists()) {
            return null;
        }

        $rawPassword = !empty($row['password']) ? $row['password'] : Str::random(8);

        return new User([
            'name'           => $name,
            'username'       => $username,
            'email'          => $email,
            'school'         => $row['school'] ?? null,
            'password'       => Hash::make($rawPassword),
            'plain_password' => Crypt::encryptString($rawPassword),
            'role'           => 0,
        ]);
    }
}
