<?php

namespace App\Http\Controllers;

use App\Models\Paechter;
use App\Models\Stellplatz;
use App\Models\Vertrag;
use Illuminate\Http\Request;

class VertragController extends Controller
{
    public function index(Request $request)
    {
        $query = Vertrag::with(['stellplatz', 'paechter'])
            ->withCount('zahlungen');

        if ($search = $request->get('search')) {
            $query->whereHas('paechter', function ($q) use ($search) {
                $q->where('vorname', 'like', "%{$search}%")
                  ->orWhere('nachname', 'like', "%{$search}%");
            })->orWhereHas('stellplatz', function ($q) use ($search) {
                $q->where('nummer', 'like', "%{$search}%");
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $vertraege = $query->latest()->paginate(20)->withQueryString();

        return view('vertraege.index', compact('vertraege'));
    }

    public function create(Request $request)
    {
        $stellplaetze = Stellplatz::aktiv()
            ->whereDoesntHave('vertraege', fn($q) => $q->where('status', 'aktiv'))
            ->orderBy('nummer')
            ->get();
        $paechter = Paechter::where('status', 'aktiv')->orderBy('nachname')->get();

        $preselect_stellplatz = $request->get('stellplatz_id');

        return view('vertraege.create', compact('stellplaetze', 'paechter', 'preselect_stellplatz'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'stellplatz_id'    => 'required|exists:stellplaetze,id',
            'paechter_id'      => 'required|exists:paechter,id',
            'beginn'           => 'required|date',
            'ende'             => 'nullable|date|after:beginn',
            'jahresbetrag'     => 'required|numeric|min:0',
            'zahlungsrhythmus' => 'required|in:jaehrlich,halbjaehrlich,vierteljaehrlich,monatlich',
            'status'           => 'required|in:aktiv,gekuendigt,beendet',
            'notizen'          => 'nullable|string',
        ]);

        Vertrag::create($validated);

        return redirect()->route('vertraege.index')
            ->with('success', 'Vertrag wurde erfolgreich angelegt.');
    }

    public function show(Vertrag $vertrag)
    {
        $vertrag->load(['stellplatz', 'paechter', 'zahlungen']);
        return view('vertraege.show', compact('vertrag'));
    }

    public function edit(Vertrag $vertrag)
    {
        $stellplaetze = Stellplatz::orderBy('nummer')->get();
        $paechter = Paechter::orderBy('nachname')->get();
        return view('vertraege.edit', compact('vertrag', 'stellplaetze', 'paechter'));
    }

    public function update(Request $request, Vertrag $vertrag)
    {
        $validated = $request->validate([
            'stellplatz_id'    => 'required|exists:stellplaetze,id',
            'paechter_id'      => 'required|exists:paechter,id',
            'beginn'           => 'required|date',
            'ende'             => 'nullable|date|after:beginn',
            'jahresbetrag'     => 'required|numeric|min:0',
            'zahlungsrhythmus' => 'required|in:jaehrlich,halbjaehrlich,vierteljaehrlich,monatlich',
            'status'           => 'required|in:aktiv,gekuendigt,beendet',
            'kuendigungsdatum' => 'nullable|date',
            'notizen'          => 'nullable|string',
        ]);

        $vertrag->update($validated);

        return redirect()->route('vertraege.show', $vertrag)
            ->with('success', 'Vertrag wurde aktualisiert.');
    }

    public function destroy(Vertrag $vertrag)
    {
        if ($vertrag->zahlungen()->where('status', 'offen')->exists()) {
            return back()->with('error', 'Vertrag kann nicht gelöscht werden, da noch offene Zahlungen bestehen.');
        }

        $vertrag->delete();

        return redirect()->route('vertraege.index')
            ->with('success', 'Vertrag wurde gelöscht.');
    }
}
