<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Address extends Model
{
    protected $fillable = [
        'addressable_type',
        'addressable_id',
        'house_no',
        'street_no',
        'street_name',
        'province_code',
        'district_code',
        'commune_code',
        'village_code',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    /**
     * Polymorphic relation
     */
    public function addressable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Province relation
     */
    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * District relation
     */
    public function district()
    {
        return $this->belongsTo(District::class);
    }

    /**
     * Commune relation
     */
    public function commune()
    {
        return $this->belongsTo(Commune::class);
    }

    /**
     * Village relation
     */
    public function village()
    {
        return $this->belongsTo(Village::class);
    }

    /**
     * Scope: default address
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
}
