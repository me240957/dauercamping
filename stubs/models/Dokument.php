<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Dokument extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'dokumente';

    protected $fillable = [
        'titel',
        'kategorie',
        'dateiname',
        'dateipfad',
        'dateityp',
        'dateigroesse',
        'beschreibung',
        'dokument_datum',
        'paechter_id',
        'vertrag_id',
    ];

    protected $casts = [
        'dokument_datum' => 'date',
        'dateigroesse'   => 'integer',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function paechter()
    {
        return $this->belongsTo(Paechter::class);
    }

    public function vertrag()
    {
        return $this->belongsTo(Vertrag::class);
    }

    // ── Scopes ───────────────────────────────────────────────────────────────

    public function scopeKategorie($query, string $kategorie)
    {
        return $query->where('kategorie', $kategorie);
    }

    // ── Accessors ────────────────────────────────────────────────────────────

    public function getKategorieLabelAttribute(): string
    {
        return match ($this->kategorie) {
            'angebot'   => 'Angebot',
            'rechnung'  => 'Rechnung',
            'zahlung'   => 'Zahlung',
            'sonstiges' => 'Sonstiges',
            default     => $this->kategorie,
        };
    }

    public function getKategorieBadgeAttribute(): string
    {
        return match ($this->kategorie) {
            'angebot'   => 'blue',
            'rechnung'  => 'amber',
            'zahlung'   => 'green',
            'sonstiges' => 'gray',
            default     => 'gray',
        };
    }

    public function getDateigroesseFormattertAttribute(): string
    {
        $bytes = $this->dateigroesse;
        if ($bytes >= 1048576) return round($bytes / 1048576, 1) . ' MB';
        if ($bytes >= 1024)    return round($bytes / 1024, 0) . ' KB';
        return $bytes . ' B';
    }

    public function getDateiIconAttribute(): string
    {
        $mime = $this->dateityp;
        if (str_contains($mime, 'pdf'))   return 'pdf';
        if (str_contains($mime, 'image')) return 'image';
        if (str_contains($mime, 'word') || str_contains($mime, 'document')) return 'word';
        if (str_contains($mime, 'sheet') || str_contains($mime, 'excel'))   return 'excel';
        return 'file';
    }

    public function getDownloadUrlAttribute(): string
    {
        return route('dokumente.download', $this);
    }
}
