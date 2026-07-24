<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Paechter extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'paechter';

    protected $fillable = [
        'vorname',
        'nachname',
        'email',
        'telefon',
        'mobil',
        'strasse',
        'plz',
        'ort',
        'geburtsdatum',
        'status',
        'notizen',
    ];

    protected $casts = [
        'geburtsdatum' => 'date',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function vertraege()
    {
        return $this->hasMany(Vertrag::class);
    }

    public function aktiveVertraege()
    {
        return $this->hasMany(Vertrag::class)->where('status', 'aktiv');
    }

    // ── Accessors ────────────────────────────────────────────────────────────

    public function getVollerNameAttribute(): string
    {
        return "{$this->vorname} {$this->nachname}";
    }

    public function getAdresseAttribute(): string
    {
        $parts = array_filter([$this->strasse, "{$this->plz} {$this->ort}"]);
        return implode(', ', $parts);
    }

    public function getStatusBadgeAttribute(): string
    {
        return $this->status === 'aktiv' ? 'success' : 'secondary';
    }
}
