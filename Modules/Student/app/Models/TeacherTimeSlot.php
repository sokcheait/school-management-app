<?php

namespace Modules\Student\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Student\Models\Teacher;
// use Modules\Student\Database\Factories\TeacherTimeSlotFactory;

class TeacherTimeSlot extends Model
{
    use HasFactory;

    protected $table="time_slots";

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'teacher_id',
        'day_of_week',
        'start_time',
        'end_time',
        'check_in_start_time',
        'check_in_end_time',
        'check_out_start_time',
        'check_out_end_time',
        'subject',
        'room',
        'is_active',
    ];

    protected $casts = [
        'day_of_week' => 'array',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
