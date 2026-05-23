<?php

namespace Modules\Student\Models;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Finance\Models\Payroll;
use Modules\Student\Models\TeacherAttendance;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Teacher extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia;

    protected $table = 'teachers';

    protected $fillable = [
        'teacher_no',
        'name_kh',
        'name_en',
        'gender',
        'email',
        'phone',
        'date_of_birth',
        'base_salary',
        'is_active',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'is_active' => 'boolean',
    ];

    // protected static function booted()
    // {
    //     static::saving(function ($model) {
    //         $model->full_name = trim(
    //             ($model->first_name ?? '') . ' ' . ($model->last_name ?? '')
    //         );
    //     });
    // }

    public function address()
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    public function classSubjects()
    {
        return $this->hasMany(ClassSubject::class);
    }
    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }
    public function attendances()
    {
        return $this->hasMany(TeacherAttendance::class);
    }
}