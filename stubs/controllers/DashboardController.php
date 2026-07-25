<?php

namespace App\Http\Controllers;

use App\Models\Stellplatz;
use App\Models\Uebernachtung;
use App\Models\Vertrag;
use App\Models\Zahlung;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $heute = Carbon::today();

        // ── Stellplätze ──────────────────────────────────────────────────────
        $stellplaetze_gesamt  = Stellplatz::where('status', 'aktiv')->count();
        $stellplaetze_belegt  = Vertrag::aktiv()->count();
        $stellplaetze_frei    = max(0, $stellplaetze_gesamt - $stellplaetze_belegt);

        // ── Verträge ─────────────────────────────────────────────────────────
        $vertraege_aktiv      = Vertrag::aktiv()->count();
        $vertraege_auslaufend = Vertrag::aktiv()
            ->whereNotNull('ende')
            ->where('ende', '>=', $heute)
            ->where('ende', '<=', $heute->copy()->addDays(90))
            ->with(['paechter', 'stellplatz'])
            ->orderBy('ende')
            ->get();

        // ── Zahlungen ────────────────────────────────────────────────────────
        $zahlungen_offen        = Zahlung::offen()->count();
        $offener_betrag         = Zahlung::offen()->sum('betrag');
        $einnahmen_jahr         = Zahlung::bezahlt()->jahr($heute->year)->sum('betrag');
        $zahlungen_ueberfaellig = Zahlung::offen()
            ->where('faellig_am', '<', $heute)
            ->with(['vertrag.paechter', 'vertrag.stellplatz'])
            ->orderBy('faellig_am')
            ->get();
        $naechste_zahlungen = Zahlung::offen()
            ->where('faellig_am', '>=', $heute)
            ->with(['vertrag.paechter', 'vertrag.stellplatz'])
            ->orderBy('faellig_am')
            ->limit(5)
            ->get();

        // ── Übernachtungen ───────────────────────────────────────────────────
        $aktuell_anwesend = Uebernachtung::with(['vertrag.paechter', 'vertrag.stellplatz'])
            ->where('datum', '<=', $heute)
            ->where('abreisedatum', '>', $heute)
            ->orderBy('abreisedatum')
            ->get();

        $anreisen_heute = Uebernachtung::with(['vertrag.paechter', 'vertrag.stellplatz'])
            ->whereDate('datum', $heute)
            ->get();

        $abreisen_heute = Uebernachtung::with(['vertrag.paechter', 'vertrag.stellplatz'])
            ->whereDate('abreisedatum', $heute)
            ->get();

        return view('dashboard.index', compact(
            'stellplaetze_gesamt', 'stellplaetze_belegt', 'stellplaetze_frei',
            'vertraege_aktiv', 'vertraege_auslaufend',
            'zahlungen_offen', 'offener_betrag', 'einnahmen_jahr',
            'zahlungen_ueberfaellig', 'naechste_zahlungen',
            'aktuell_anwesend', 'anreisen_heute', 'abreisen_heute'
        ));
    }
}
