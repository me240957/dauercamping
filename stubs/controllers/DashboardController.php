<?php

namespace App\Http\Controllers;

use App\Models\Paechter;
use App\Models\Stellplatz;
use App\Models\Vertrag;
use App\Models\Zahlung;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'stellplaetze_gesamt'    => Stellplatz::count(),
            'stellplaetze_verpachtet'=> Vertrag::aktiv()->count(),
            'paechter_aktiv'         => Paechter::where('status', 'aktiv')->count(),
            'zahlungen_offen'        => Zahlung::offen()->count(),
            'offener_betrag'         => Zahlung::offen()->sum('betrag'),
            'einnahmen_laufendes_jahr' => Zahlung::bezahlt()->jahr(now()->year)->sum('betrag'),
        ];

        $naechste_zahlungen = Zahlung::offen()
            ->with(['vertrag.paechter', 'vertrag.stellplatz'])
            ->orderBy('faellig_am')
            ->limit(5)
            ->get();

        $letzte_vertraege = Vertrag::with(['paechter', 'stellplatz'])
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard.index', compact('stats', 'naechste_zahlungen', 'letzte_vertraege'));
    }
}
