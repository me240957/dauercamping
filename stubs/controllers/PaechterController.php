<?php

namespace App\Http\Controllers;

use App\Models\Paechter;
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
