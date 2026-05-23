<?php

namespace Modules\Student\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Student\Database\Factories\TeacherAttendanceFactory;

class TeacherAttendance extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'teacher_id',
        'attendance_date',
        'status',
        'check_in_status',
        'check_out_status',
        'check_in',
        'check_out',
        'note',
    ];

    protected $casts = [
        'attendance_date' => 'date',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
