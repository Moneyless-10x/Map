<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TriageLocation extends Model
{
    use HasFactory;

    protected $table = 'triage_locations';

    protected $fillable = [
        'nama_lokasi',
        'kategori',
        'alamat',
        'province_id',
        'regency_id',
        'district_id',
        'village_id',
        'latitude',
        'longitude',
        'tier',
    ];

    /**
     * Type casting for attributes.
     */
    protected $casts = [
        'latitude' => 'double',
        'longitude' => 'double',
    ];

    /**
     * Relationship to the triage patient records assigned to this location.
     */
    public function triages(): HasMany
    {
        return $this->hasMany(Triage::class, 'location_id');
    }
}