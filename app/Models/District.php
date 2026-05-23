<?php

namespace App\Models;

use App\Models\Commune;
use App\Models\Province;
use App\Models\Village;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $fillable = [
        'province_code',
        'district_code',
        'district_en',
        'district_kh',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_code', 'province_code');
    }

    public function communes()
    {
        return $this->hasMany(Commune::class, 'district_code', 'district_code');
    }

    public function villages()
    {
        return $this->hasManyThrough(
            Village::class,
            Commune::class,
            'district_code',
            'commune_code',
            'district_code',
            'commune_code'
        );
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
