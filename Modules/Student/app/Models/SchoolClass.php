<?php

namespace Modules\Student\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Student\Models\Student;

class SchoolClass extends Model
{
    use HasFactory;

    protected $table = 'school_classes';

    protected $fillable = [
        'name',
        'section',
        'academic_year',
    ];

   public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'enrollments');
    }
    
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'class_subject')
            ->withPivot(['teacher_id', 'hours_per_week', 'semester'])
            ->withTimestamps();
    }
}