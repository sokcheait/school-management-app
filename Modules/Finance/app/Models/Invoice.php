<?php

namespace Modules\Finance\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'student_id',
        'currency_id',
        'invoice_no',
        'invoice_date',
        'due_date',
        'subtotal',
        'discount',
        'total',
        'paid_amount',
        'balance',
        'status',
        'note',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(
            \Modules\Student\Models\Student::class
        );
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments()
    {
        return $this->hasMany(StudentPayment::class);
    }
}
