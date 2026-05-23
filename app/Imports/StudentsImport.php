<?php

namespace App\Imports;

use Modules\Student\Models\Student;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Row;

class StudentsImport implements OnEachRow, WithHeadingRow
{
    public function onRow(Row $row)
    {
        $data = $row->toArray();

        // 1. Find by email or create a new instance
        $student = Student::updateOrCreate(
            ['email' => $data['email']], // Unique identifier to check
            [
                'name_kh'       => $data['name_kh'] ?? null,
                'name_en'       => $data['name_en'] ?? null,
                'gender'        => $data['gender'] ?? null,
                'phone'         => $data['phone'] ?? null,
                'date_of_birth' => $data['date_of_birth'] ?? null,
                'is_active'     => $data['is_active'] ?? 1,
            ]
        );

        // 2. Handle the address (update if exists, otherwise create)
        $student->address()->updateOrCreate(
            ['addressable_id' => $student->id,'addressable_type' => get_class($student)],
            [
                'house_no'      => $data['house_no'] ?? null,
                'street_no'     => $data['street_no'] ?? null,
                'street_name'   => $data['street_name'] ?? null,
                'province_code' => $data['province_code'] ?? null,
                'district_code' => $data['district_code'] ?? null,
                'commune_code'  => $data['commune_code'] ?? null,
                'village_code'  => $data['village_code'] ?? null,
            ]
        );
    }
}