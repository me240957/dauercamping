<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vertrag extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'vertraege';

    protected $fillable = [
        'stellplatz_id',
        'paechter_id',
        'beginn',
        'ende',
        'jahresbetrag',
        'zahlungsrhythmus',
        'status',
        'kuendigungsdatum',
        'notizen',
    ];

    protected $casts = [
        'beginn'           => 'date',
        'ende'             => 'date',
        'kuendigungsdatum' => 'date',
        'jahresbetrag'     => 'decimal:2',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function stellplatz()
    {
        return $this->belongsTo(Stellplatz::class);
    }

    public function paechter()
    {
        return $this->belongsTo(Paechter::class);
    }

    public function zahlungen()
    {
        return $this->hasMany(Zahlung::class);
    }

    public function uebernachtungen()
    {
        return $this->hasMany(Uebernachtung::class);
    }

    // ── Scopes ───────────────────────────────────────────────────────────────

    public function scopeAktiv($query)
    {
        return $query->where('status', 'aktiv');
    }

    // ── Accessors ────────────────────────────────────────────────────────────

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'aktiv'      => 'success',
            'gekuendigt' => 'warning',
            'beendet'    => 'secondary',
            default      => 'secondary',
        };
    }

    public function getZahlungsrhythmusLabelAttribute(): string
    {
        return match ($this->zahlungsrhythmus) {
            'jaehrlich'       => 'Jährlich',
            'halbjaehrlich'   => 'Halbjährlich',
            'vierteljaehrlich' => 'Vierteljährlich',
            'monatlich'       => 'Monatlich',
            default           => $this->zahlungsrhythmus,
        };
    }

    public function getOffeneBetragAttribute(): float
    {
        return (float) $this->zahlungen()->where('status', 'offen')->sum('betrag');
    }
}
