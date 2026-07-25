<?php

namespace App\Http\Controllers;

use App\Models\Dokument;
use App\Models\Paechter;
use App\Models\Vertrag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DokumentController extends Controller
{
    private const ERLAUBTE_TYPEN = [
        'application/pdf',
        'image/jpeg', 'image/png', 'image/gif', 'image/webp',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ];

    private const MAX_GROESSE_MB = 20;

    public function index(Request $request)
    {
        $query = Dokument::with(['paechter', 'vertrag.stellplatz'])
            ->orderByDesc('created_at');

        if ($kategorie = $request->get('kategorie')) {
            $query->where('kategorie', $kategorie);
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('titel', 'like', "%{$search}%")
                  ->orWhere('beschreibung', 'like', "%{$search}%")
                  ->orWhere('dateiname', 'like', "%{$search}%");
            });
        }

        if ($paechter_id = $request->get('paechter_id')) {
            $query->where('paechter_id', $paechter_id);
        }

        $dokumente = $query->paginate(15)->withQueryString();
        $paechter  = Paechter::orderBy('nachname')->get();

        return view('dokumente.index', compact('dokumente', 'paechter'));
    }

    public function create()
    {
        $paechter = Paechter::orderBy('nachname')->get();
        $vertraege = Vertrag::aktiv()->with(['paechter', 'stellplatz'])->get();
        return view('dokumente.create', compact('paechter', 'vertraege'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titel'          => 'required|string|max:255',
            'kategorie'      => 'required|in:angebot,rechnung,zahlung,sonstiges',
            'datei'          => 'required|file|mimes:pdf,jpg,jpeg,png,gif,webp,doc,docx,xls,xlsx|max:' . (self::MAX_GROESSE_MB * 1024),
            'beschreibung'   => 'nullable|string',
            'dokument_datum' => 'nullable|date',
            'paechter_id'    => 'nullable|exists:paechter,id',
            'vertrag_id'     => 'nullable|exists:vertraege,id',
        ]);

        $datei      = $request->file('datei');
        $dateiname  = $datei->getClientOriginalName();
        $dateipfad  = $datei->storeAs(
            'dokumente/' . $validated['kategorie'],
            Str::uuid() . '.' . $datei->getClientOriginalExtension(),
            'private'
        );

        Dokument::create([
            'titel'          => $validated['titel'],
            'kategorie'      => $validated['kategorie'],
            'dateiname'      => $dateiname,
            'dateipfad'      => $dateipfad,
            'dateityp'       => $datei->getMimeType(),
            'dateigroesse'   => $datei->getSize(),
            'beschreibung'   => $validated['beschreibung'] ?? null,
            'dokument_datum' => $validated['dokument_datum'] ?? null,
            'paechter_id'    => $validated['paechter_id'] ?? null,
            'vertrag_id'     => $validated['vertrag_id'] ?? null,
        ]);

        return redirect()->route('dokumente.index')
            ->with('success', 'Dokument wurde erfolgreich hochgeladen.');
    }

    public function show(Dokument $dokument)
    {
        $dokument->load(['paechter', 'vertrag.stellplatz']);
        return view('dokumente.show', compact('dokument'));
    }

    public function edit(Dokument $dokument)
    {
        $paechter  = Paechter::orderBy('nachname')->get();
        $vertraege = Vertrag::with(['paechter', 'stellplatz'])->get();
        return view('dokumente.edit', compact('dokument', 'paechter', 'vertraege'));
    }

    public function update(Request $request, Dokument $dokument)
    {
        $validated = $request->validate([
            'titel'          => 'required|string|max:255',
            'kategorie'      => 'required|in:angebot,rechnung,zahlung,sonstiges',
            'datei'          => 'nullable|file|mimes:pdf,jpg,jpeg,png,gif,webp,doc,docx,xls,xlsx|max:' . (self::MAX_GROESSE_MB * 1024),
            'beschreibung'   => 'nullable|string',
            'dokument_datum' => 'nullable|date',
            'paechter_id'    => 'nullable|exists:paechter,id',
            'vertrag_id'     => 'nullable|exists:vertraege,id',
        ]);

        // Neue Datei hochgeladen?
        if ($request->hasFile('datei')) {
            Storage::disk('private')->delete($dokument->dateipfad);

            $datei = $request->file('datei');
            $dokument->dateiname    = $datei->getClientOriginalName();
            $dokument->dateipfad    = $datei->storeAs(
                'dokumente/' . $validated['kategorie'],
                Str::uuid() . '.' . $datei->getClientOriginalExtension(),
                'private'
            );
            $dokument->dateityp     = $datei->getMimeType();
            $dokument->dateigroesse = $datei->getSize();
        }

        $dokument->titel          = $validated['titel'];
        $dokument->kategorie      = $validated['kategorie'];
        $dokument->beschreibung   = $validated['beschreibung'] ?? null;
        $dokument->dokument_datum = $validated['dokument_datum'] ?? null;
        $dokument->paechter_id    = $validated['paechter_id'] ?? null;
        $dokument->vertrag_id     = $validated['vertrag_id'] ?? null;
        $dokument->save();

        return redirect()->route('dokumente.show', $dokument)
            ->with('success', 'Dokument wurde aktualisiert.');
    }

    public function download(Dokument $dokument)
    {
        if (!Storage::disk('private')->exists($dokument->dateipfad)) {
            abort(404, 'Datei nicht gefunden.');
        }

        return Storage::disk('private')->download(
            $dokument->dateipfad,
            $dokument->dateiname
        );
    }

    public function destroy(Dokument $dokument)
    {
        Storage::disk('private')->delete($dokument->dateipfad);
        $dokument->delete();

        return redirect()->route('dokumente.index')
            ->with('success', 'Dokument wurde gelöscht.');
    }
}
