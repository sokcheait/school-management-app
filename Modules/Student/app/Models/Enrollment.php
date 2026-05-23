<?php

namespace Modules\Student\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Enrollment extends Model
{
    use HasFactory;

    protected $table = 'enrollments';

    protected $fillable = [
        'student_id',
        'school_class_id',
        'enrolled_at',
        'status',
    ];

    protected $casts = [
        'enrolled_at' => 'date',
    ];

    // 🔗 Relationship: Enrollment belongs to Student
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // 🔗 Relationship: Enrollment belongs to Class
    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class);
    }
}