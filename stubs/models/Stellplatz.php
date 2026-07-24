<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stellplatz extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'stellplaetze';

    protected $fillable = [
        'nummer',
        'bezeichnung',
        'groesse_qm',
        'lage',
        'status',
        'notizen',
    ];

    protected $casts = [
        'groesse_qm' => 'decimal:2',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function vertraege()
    {
        return $this->hasMany(Vertrag::class);
    }

    public function aktiverVertrag()
    {
        return $this->hasOne(Vertrag::class)->where('status', 'aktiv')->latest();
    }

    // ── Scopes ───────────────────────────────────────────────────────────────

    public function scopeAktiv($query)
    {
        return $query->where('status', 'aktiv');
    }

    public function scopeVerfuegbar($query)
    {
        return $query->aktiv()->whereDoesntHave('vertraege', fn($q) => $q->where('status', 'aktiv'));
    }

    // ── Accessors ────────────────────────────────────────────────────────────

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'aktiv'    => 'success',
            'inaktiv'  => 'secondary',
            'gesperrt' => 'danger',
            default    => 'secondary',
        };
    }

    public function getIstVerpachtetAttribute(): bool
    {
        return $this->vertraege()->where('status', 'aktiv')->exists();
    }
}
