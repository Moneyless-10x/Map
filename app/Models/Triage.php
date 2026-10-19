<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Triage extends Model
{
    use HasFactory;

    protected $fillable = [
        'location_id',
        'bedUpdated',
        'priority',
        'kategoriKeluhan',
        'catatanPerawat',
        'isDNRS',
        'foto_triase',
        'foto_trauma',
        'foto_trauma_upload',
    ];

    /**
     * Get the location that owns the triage record.
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(TriageLocation::class, 'location_id');
    }
}