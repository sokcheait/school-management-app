<?php
namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Modules\Student\Models\Teacher;

class TeacherImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Teacher([
            'name_kh'       => $row['name_kh'],
            'name_en'       => $row['name_en'],
            'gender'        => $row['gender'],
            'email'         => $row['email'],
            'phone'         => $row['phone'],
            'address'       => $row['address'],
            'date_of_birth' => $row['date_of_birth'],
            'is_active'     => $row['is_active'] ?? 1,
        ]);
    }
}