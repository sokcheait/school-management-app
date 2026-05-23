<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Row;
use Modules\Student\Models\SchoolClass;

class SchoolClassImport implements OnEachRow, WithHeadingRow
{
    public function onRow(Row $row)
    {
        $data = $row->toArray();

        $school_class = SchoolClass::create([
            'name'          => $data['name'] ?? null,
            'section'       => $data['section'] ?? null,
            'academic_year' => $data['academic_year'] ?? null,
        ]);
    }
}