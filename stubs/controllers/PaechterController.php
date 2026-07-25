<?php

namespace App\Http\Controllers;

use App\Models\Paechter;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PaechterController extends Controller
{
    public function index(Request $request)
    {
        $query = Paechter::withCount('vertraege');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('vorname', 'like', "%{$search}%")
                  ->orWhere('nachname', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('ort', 'like', "%{$search}%");
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $paechter = $query->orderBy('nachname')->orderBy('vorname')->paginate(20)->withQueryString();

        return view('paechter.index', compact('paechter'));
    }

    public function create()
    {
        return view('paechter.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vorname'       => 'required|string|max:100',
            'nachname'      => 'required|string|max:100',
            'email'         => 'nullable|email|unique:paechter',
            'telefon'       => 'nullable|string|max:30',
            'mobil'         => 'nullable|string|max:30',
            'strasse'       => 'nullable|string|max:255',
            'plz'           => 'nullable|string|max:10',
            'ort'           => 'nullable|string|max:100',
            'geburtsdatum'  => 'nullable|date',
            'status'        => 'required|in:aktiv,inaktiv',
            'notizen'       => 'nullable|string',
        ]);

        Paechter::create($validated);

        return redirect()->route('paechter.index')
            ->with('success', 'Pächter wurde erfolgreich angelegt.');
    }

    public function show(Paechter $paechter)
    {
        $paechter->load(['vertraege.stellplatz', 'vertraege.zahlungen']);
        return view('paechter.show', compact('paechter'));
    }

    public function edit(Paechter $paechter)
    {
        return view('paechter.edit', compact('paechter'));
    }

    public function update(Request $request, Paechter $paechter)
    {
        $validated = $request->validate([
            'vorname'       => 'required|string|max:100',
            'nachname'      => 'required|string|max:100',
            'email'         => 'nullable|email|unique:paechter,email,' . $paechter->id,
            'telefon'       => 'nullable|string|max:30',
            'mobil'         => 'nullable|string|max:30',
            'strasse'       => 'nullable|string|max:255',
            'plz'           => 'nullable|string|max:10',
            'ort'           => 'nullable|string|max:100',
            'geburtsdatum'  => 'nullable|date',
            'status'        => 'required|in:aktiv,inaktiv',
            'notizen'       => 'nullable|string',
        ]);

        $paechter->update($validated);

        return redirect()->route('paechter.show', $paechter)
            ->with('success', 'Pächter wurde aktualisiert.');
    }

    public function jahresabrechnung(Request $request, Paechter $paechter)
    {
        $jahr = (int) $request->get('jahr', now()->year);

        // Alle Verträge des Pächters laden
        $paechter->load([
            'vertraege.stellplatz',
            'vertraege.zahlungen' => fn($q) => $q->where('jahr', $jahr)->orderBy('faellig_am'),
            'vertraege.uebernachtungen' => fn($q) => $q->whereYear('datum', $jahr)->orderBy('datum'),
        ]);

        // Nur Verträge die im Jahr aktiv waren
        $vertraege = $paechter->vertraege->filter(function ($v) use ($jahr) {
            $beginn = $v->beginn?->year ?? 0;
            $ende   = $v->ende?->year   ?? 9999;
            return $beginn <= $jahr && $ende >= $jahr;
        });

        // Jahres-Summen
        $summen = [
            'zahlungen_bezahlt' => 0,
            'zahlungen_offen'   => 0,
            'naechte'           => 0,
            'personennaechte'   => 0,
        ];

        foreach ($vertraege as $v) {
            foreach ($v->zahlungen as $z) {
                if ($z->status === 'bezahlt') $summen['zahlungen_bezahlt'] += $z->betrag;
                else                          $summen['zahlungen_offen']   += $z->betrag;
            }
            foreach ($v->uebernachtungen as $u) {
                $summen['naechte']        += $u->anzahl_naechte;
                $summen['personennaechte'] += $u->personennaechte;
            }
        }

        $pdf = Pdf::loadView('paechter.jahresabrechnung_pdf', compact(
            'paechter', 'vertraege', 'jahr', 'summen'
        ))->setPaper('a4', 'portrait');

        $dateiname = 'jahresabrechnung-' . $paechter->nachname . '-' . $jahr . '.pdf';

        return $pdf->download($dateiname);
    }

    public function destroy(Paechter $paechter)
    {
        if ($paechter->vertraege()->where('status', 'aktiv')->exists()) {
            return back()->with('error', 'Pächter kann nicht gelöscht werden, da noch aktive Verträge bestehen.');
        }

        $paechter->delete();

        return redirect()->route('paechter.index')
            ->with('success', 'Pächter wurde gelöscht.');
    }
}
