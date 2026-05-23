<?php

namespace Modules\Student\Models;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Finance\Models\Invoice;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Modules\Student\Models\Enrollment;
use Modules\Student\Models\SchoolClass;
// use Modules\Student\Database\Factories\StudentFactory;

class Student extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'students';

    protected $fillable = [
        'name_kh',
        'name_en',
        'gender',
        'email',
        'phone',
        'date_of_birth',
        'is_active',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'is_active' => 'boolean',
    ];

    // protected static function booted()
    // {
    //     static::saving(function ($student) {
    //         $student->full_name = trim($student->name_kh . ' ' . $student->name_en);
    //     });
    // }

    // protected static function newFactory(): StudentFactory
    // {
    //     // return StudentFactory::new();
    // }
    

    public function address()
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function classes()
    {
        return $this->belongsToMany(SchoolClass::class, 'enrollments');
    }
    
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
