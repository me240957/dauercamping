<?php

namespace App\Http\Controllers;

use App\Models\Vertrag;
use App\Models\Zahlung;
use Illuminate\Http\Request;

class ZahlungController extends Controller
{
    public function index(Request $request)
    {
        $query = Zahlung::with(['vertrag.paechter', 'vertrag.stellplatz']);

        if ($search = $request->get('search')) {
            $query->whereHas('vertrag.paechter', function ($q) use ($search) {
                $q->where('vorname', 'like', "%{$search}%")
                  ->orWhere('nachname', 'like', "%{$search}%");
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($jahr = $request->get('jahr')) {
            $query->where('jahr', $jahr);
        }

        $zahlungen = $query->orderBy('faellig_am')->paginate(25)->withQueryString();

        $jahre = Zahlung::selectRaw('DISTINCT jahr')->orderByDesc('jahr')->pluck('jahr');

        return view('zahlungen.index', compact('zahlungen', 'jahre'));
    }

    public function create(Request $request)
    {
        $vertraege = Vertrag::aktiv()->with(['paechter', 'stellplatz'])->get();
        $preselect_vertrag = $request->get('vertrag_id');
        return view('zahlungen.create', compact('vertraege', 'preselect_vertrag'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vertrag_id'  => 'required|exists:vertraege,id',
            'jahr'        => 'required|integer|min:2000|max:2100',
            'betrag'      => 'required|numeric|min:0',
            'faellig_am'  => 'nullable|date',
            'bezahlt_am'  => 'nullable|date',
            'status'      => 'required|in:offen,bezahlt,gemahnt,storniert',
            'zahlungsart' => 'nullable|string|max:100',
            'referenz'    => 'nullable|string|max:255',
            'notizen'     => 'nullable|string',
        ]);

        Zahlung::create($validated);

        return redirect()->route('zahlungen.index')
            ->with('success', 'Zahlung wurde erfolgreich angelegt.');
    }

    public function show(Zahlung $zahlung)
    {
        $zahlung->load(['vertrag.paechter', 'vertrag.stellplatz']);
        return view('zahlungen.show', compact('zahlung'));
    }

    public function edit(Zahlung $zahlung)
    {
        $vertraege = Vertrag::with(['paechter', 'stellplatz'])->get();
        return view('zahlungen.edit', compact('zahlung', 'vertraege'));
    }

    public function update(Request $request, Zahlung $zahlung)
    {
        $validated = $request->validate([
            'vertrag_id'  => 'required|exists:vertraege,id',
            'jahr'        => 'required|integer|min:2000|max:2100',
            'betrag'      => 'required|numeric|min:0',
            'faellig_am'  => 'nullable|date',
            'bezahlt_am'  => 'nullable|date',
            'status'      => 'required|in:offen,bezahlt,gemahnt,storniert',
            'zahlungsart' => 'nullable|string|max:100',
            'referenz'    => 'nullable|string|max:255',
            'notizen'     => 'nullable|string',
        ]);

        $zahlung->update($validated);

        return redirect()->route('zahlungen.show', $zahlung)
            ->with('success', 'Zahlung wurde aktualisiert.');
    }

    public function alsBezahltMarkieren(Zahlung $zahlung)
    {
        $zahlung->update([
            'status'     => 'bezahlt',
            'bezahlt_am' => now()->toDateString(),
        ]);

        return back()->with('success', 'Zahlung wurde als bezahlt markiert.');
    }

    public function destroy(Zahlung $zahlung)
    {
        $zahlung->delete();
        return redirect()->route('zahlungen.index')
            ->with('success', 'Zahlung wurde gelöscht.');
    }
}
