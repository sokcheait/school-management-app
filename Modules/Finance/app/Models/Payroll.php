<?php

namespace Modules\Finance\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Finance\Models\PayrollItem;
use Modules\Student\Models\Teacher;
// use Modules\Finance\Database\Factories\PayrollFactory;

class Payroll extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */

    protected $fillable = [
        'teacher_id',
        'month',
        'year',
        'base_salary',
        'allowance',
        'deduction',
        'net_salary',
        'status',
        'paid_at',
    ];

    protected static function booted()
    {
        static::saving(function ($model) {

            $model->net_salary =
                $model->base_salary +
                $model->allowance -
                $model->deduction;
        });
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function items()
    {
        return $this->hasMany(PayrollItem::class);
    }
}
