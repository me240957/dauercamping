<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Uebernachtung extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'uebernachtungen';

    protected $fillable = [
        'vertrag_id',
        'datum',
        'abreisedatum',
        'anzahl_naechte',
        'anzahl_personen',
        'notizen',
    ];

    protected $casts = [
        'datum'           => 'date',
        'abreisedatum'    => 'date',
        'anzahl_naechte'  => 'integer',
        'anzahl_personen' => 'integer',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function vertrag()
    {
        return $this->belongsTo(Vertrag::class);
    }

    // ── Scopes ───────────────────────────────────────────────────────────────

    public function scopeJahr($query, int $jahr)
    {
        return $query->whereYear('datum', $jahr);
    }

    public function scopeMonat($query, int $jahr, int $monat)
    {
        return $query->whereYear('datum', $jahr)->whereMonth('datum', $monat);
    }

    // ── Accessors ────────────────────────────────────────────────────────────

    public function getPersonennaechteAttribute(): int
    {
        return $this->anzahl_naechte * $this->anzahl_personen;
    }
}
