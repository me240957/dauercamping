<?php

namespace App\Http\Controllers;

use App\Models\Stellplatz;
use Illuminate\Http\Request;

class StellplatzController extends Controller
{
    public function index(Request $request)
    {
        $query = Stellplatz::withCount('vertraege')
            ->with('aktiverVertrag.paechter');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nummer', 'like', "%{$search}%")
                  ->orWhere('bezeichnung', 'like', "%{$search}%")
                  ->orWhere('lage', 'like', "%{$search}%");
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $stellplaetze = $query->orderBy('nummer')->paginate(20)->withQueryString();

        return view('stellplaetze.index', compact('stellplaetze'));
    }

    public function create()
    {
        return view('stellplaetze.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nummer'       => 'required|string|max:20|unique:stellplaetze',
            'bezeichnung'  => 'nullable|string|max:255',
            'groesse_qm'   => 'nullable|numeric|min:0',
            'lage'         => 'nullable|string|max:255',
            'status'       => 'required|in:aktiv,inaktiv,gesperrt',
            'notizen'      => 'nullable|string',
        ]);

        Stellplatz::create($validated);

        return redirect()->route('stellplaetze.index')
            ->with('success', 'Stellplatz wurde erfolgreich angelegt.');
    }

    public function show(Stellplatz $stellplatz)
    {
        $stellplatz->load(['vertraege.paechter', 'vertraege.zahlungen']);
        return view('stellplaetze.show', compact('stellplatz'));
    }

    public function edit(Stellplatz $stellplatz)
    {
        return view('stellplaetze.edit', compact('stellplatz'));
    }

    public function update(Request $request, Stellplatz $stellplatz)
    {
        $validated = $request->validate([
            'nummer'       => 'required|string|max:20|unique:stellplaetze,nummer,' . $stellplatz->id,
            'bezeichnung'  => 'nullable|string|max:255',
            'groesse_qm'   => 'nullable|numeric|min:0',
            'lage'         => 'nullable|string|max:255',
            'status'       => 'required|in:aktiv,inaktiv,gesperrt',
            'notizen'      => 'nullable|string',
        ]);

        $stellplatz->update($validated);

        return redirect()->route('stellplaetze.show', $stellplatz)
            ->with('success', 'Stellplatz wurde aktualisiert.');
    }

    public function destroy(Stellplatz $stellplatz)
    {
        if ($stellplatz->vertraege()->where('status', 'aktiv')->exists()) {
            return back()->with('error', 'Stellplatz kann nicht gelöscht werden, da noch ein aktiver Vertrag besteht.');
        }

        $stellplatz->delete();

        return redirect()->route('stellplaetze.index')
            ->with('success', 'Stellplatz wurde gelöscht.');
    }
}
