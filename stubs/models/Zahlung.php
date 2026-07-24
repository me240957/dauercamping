<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Zahlung extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'zahlungen';

    protected $fillable = [
        'vertrag_id',
        'jahr',
        'betrag',
        'faellig_am',
        'bezahlt_am',
        'status',
        'zahlungsart',
        'referenz',
        'notizen',
    ];

    protected $casts = [
        'faellig_am' => 'date',
        'bezahlt_am' => 'date',
        'betrag'     => 'decimal:2',
        'jahr'       => 'integer',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function vertrag()
    {
        return $this->belongsTo(Vertrag::class);
    }

    // ── Scopes ───────────────────────────────────────────────────────────────

    public function scopeOffen($query)
    {
        return $query->where('status', 'offen');
    }

    public function scopeBezahlt($query)
    {
        return $query->where('status', 'bezahlt');
    }

    public function scopeJahr($query, int $jahr)
    {
        return $query->where('jahr', $jahr);
    }

    // ── Accessors ────────────────────────────────────────────────────────────

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'bezahlt'   => 'success',
            'offen'     => 'warning',
            'gemahnt'   => 'danger',
            'storniert' => 'secondary',
            default     => 'secondary',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'bezahlt'   => 'Bezahlt',
            'offen'     => 'Offen',
            'gemahnt'   => 'Gemahnt',
            'storniert' => 'Storniert',
            default     => $this->status,
        };
    }

    public function getIstUeberfaelligAttribute(): bool
    {
        return $this->status === 'offen'
            && $this->faellig_am
            && $this->faellig_am->isPast();
    }
}
