<?php

namespace App\Imports;

use Modules\Student\Models\Subject;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SubjectsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Subject([
            'name' => $row['name'],
            'code' => $row['code'] ?? null,
            'description' => $row['description'] ?? null,
            'is_active' => isset($row['is_active']) ? (bool) $row['is_active'] : true,
        ]);
    }
}