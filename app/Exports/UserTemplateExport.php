<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UserTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return [
            'Name',
            'Username',
            'Email',
            'School',
            'Password'
        ];
    }

    public function array(): array
    {
        return [
            ['John Doe', 'johndoe', 'johndoe@example.com', 'High School 1', 'secret123'],
            ['Jane Doe', 'janedoe', 'janedoe@example.com', 'High School 2', ''] // empty password means auto-generated
        ];
    }
}
