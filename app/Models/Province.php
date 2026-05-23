<?php

namespace App\Models;

use App\Models\Commune;
use App\Models\District;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
     protected $fillable = [
        'province_code',
        'province_kh',
        'province_en',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function districts()
    {
        return $this->hasMany(District::class, 'province_code', 'province_code');
    }

    public function communes()
    {
        return $this->hasManyThrough(
            Commune::class,
            District::class,
            'province_code',   // Foreign key on districts
            'district_code',   // Foreign key on communes
            'province_code',   // Local key on provinces
            'district_code'    // Local key on districts
        );
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
