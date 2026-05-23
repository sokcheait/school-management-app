<?php

namespace App\Models;

use App\Models\District;
use App\Models\Province;
use App\Models\Village;
use Illuminate\Database\Eloquent\Model;

class Commune extends Model
{
    protected $fillable = [
        'province_code',
        'district_code',
        'commune_code',
        'commune_kh',
        'commune_en',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_code', 'province_code');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_code', 'district_code');
    }

    public function villages()
    {
        return $this->hasMany(Village::class, 'commune_code', 'commune_code');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
