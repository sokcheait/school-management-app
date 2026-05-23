<?php

namespace Modules\Finance\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Student\Models\Student;

class StudentPayment extends Model
{
    protected $fillable = [
        'invoice_id',
        'student_id',
        'currency_id',
        'amount',
        'exchange_rate',
        'base_amount',
        'discount',
        'received_amount',
        'change_amount',
        'paid_date',
        'payment_method',
        'receipt_no',
        'note',
    ];

    protected $casts = [
        'paid_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function getFormattedAmountAttribute(): string
    {
        $symbol = $this->currency?->symbol ?? '$';

        return $symbol . number_format($this->amount, 2);
    }
}
