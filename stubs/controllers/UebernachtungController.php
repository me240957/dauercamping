<?php

namespace App\Http\Controllers;

use App\Models\Uebernachtung;
use App\Models\Vertrag;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UebernachtungController extends Controller
{
    public function index(Request $request)
    {
        $query = Uebernachtung::with(['vertrag.paechter', 'vertrag.stellplatz'])
            ->orderByDesc('datum');

        if ($search = $request->get('search')) {
            $query->whereHas('vertrag.paechter', function ($q) use ($search) {
                $q->where('vorname', 'like', "%{$search}%")
                  ->orWhere('nachname', 'like', "%{$search}%");
            })->orWhereHas('vertrag.stellplatz', function ($q) use ($search) {
                $q->where('nummer', 'like', "%{$search}%");
            });
        }

        if ($jahr = $request->get('jahr')) {
            $query->whereYear('datum', $jahr);
        }

        $uebernachtungen = $query->paginate(7)->withQueryString();

        $jahre = Uebernachtung::selectRaw('DISTINCT YEAR(datum) as jahr')
            ->orderByDesc('jahr')
            ->pluck('jahr');

        return view('uebernachtungen.index', compact('uebernachtungen', 'jahre'));
    }

    public function create(Request $request)
    {
        $vertraege = Vertrag::aktiv()->with(['paechter', 'stellplatz'])->get();
        $preselect_vertrag = $request->get('vertrag_id');
        return view('uebernachtungen.create', compact('vertraege', 'preselect_vertrag'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vertrag_id'      => 'required|exists:vertraege,id',
            'datum'           => 'required|date',
            'abreisedatum'    => 'required|date|after:datum',
            'anzahl_personen' => 'required|integer|min:1|max:50',
            'notizen'         => 'nullable|string',
        ]);

        $validated['anzahl_naechte'] = Carbon::parse($validated['datum'])
            ->diffInDays(Carbon::parse($validated['abreisedatum']));

        Uebernachtung::create($validated);

        return redirect()->route('uebernachtungen.index')
            ->with('success', 'Übernachtung wurde erfasst.');
    }

    public function edit(Uebernachtung $uebernachtung)
    {
        $vertraege = Vertrag::with(['paechter', 'stellplatz'])->get();
        return view('uebernachtungen.edit', compact('uebernachtung', 'vertraege'));
    }

    public function update(Request $request, Uebernachtung $uebernachtung)
    {
        $validated = $request->validate([
            'vertrag_id'      => 'required|exists:vertraege,id',
            'datum'           => 'required|date',
            'abreisedatum'    => 'required|date|after:datum',
            'anzahl_personen' => 'required|integer|min:1|max:50',
            'notizen'         => 'nullable|string',
        ]);

        $validated['anzahl_naechte'] = Carbon::parse($validated['datum'])
            ->diffInDays(Carbon::parse($validated['abreisedatum']));

        $uebernachtung->update($validated);

        return redirect()->route('uebernachtungen.index')
            ->with('success', 'Übernachtung wurde aktualisiert.');
    }

    public function destroy(Uebernachtung $uebernachtung)
    {
        $uebernachtung->delete();
        return redirect()->route('uebernachtungen.index')
            ->with('success', 'Übernachtung wurde gelöscht.');
    }

    // ── Statistik ─────────────────────────────────────────────────────────────

    public function statistik(Request $request)
    {
        $jahr = (int) $request->get('jahr', now()->year);

        // Nächte pro Monat
        $proMonat = Uebernachtung::selectRaw('
                MONTH(datum) as monat,
                SUM(anzahl_naechte) as naechte,
                SUM(anzahl_personen) as personen,
                SUM(anzahl_naechte * anzahl_personen) as pn_summe
            ')
            ->whereYear('datum', $jahr)
            ->groupByRaw('MONTH(datum)')
            ->orderByRaw('MONTH(datum)')
            ->get()
            ->keyBy('monat');

        // Nächte pro Stellplatz
        $proStellplatz = Uebernachtung::selectRaw('
                vertraege.stellplatz_id,
                SUM(uebernachtungen.anzahl_naechte) as naechte,
                SUM(uebernachtungen.anzahl_naechte * uebernachtungen.anzahl_personen) as personennaechte
            ')
            ->join('vertraege', 'vertraege.id', '=', 'uebernachtungen.vertrag_id')
            ->whereYear('uebernachtungen.datum', $jahr)
            ->groupBy('vertraege.stellplatz_id')
            ->orderByDesc('naechte')
            ->with('vertrag.stellplatz')
            ->get();

        // Nächte pro Pächter
        $proPaechter = Uebernachtung::selectRaw('
                vertraege.paechter_id,
                SUM(uebernachtungen.anzahl_naechte) as naechte,
                SUM(uebernachtungen.anzahl_naechte * uebernachtungen.anzahl_personen) as personennaechte
            ')
            ->join('vertraege', 'vertraege.id', '=', 'uebernachtungen.vertrag_id')
            ->whereYear('uebernachtungen.datum', $jahr)
            ->groupBy('vertraege.paechter_id')
            ->orderByDesc('naechte')
            ->limit(10)
            ->get();

        // Pächter-Namen nachladen
        $paechterIds = $proPaechter->pluck('paechter_id');
        $paechterMap = \App\Models\Paechter::whereIn('id', $paechterIds)->get()->keyBy('id');

        // Stellplatz-Namen nachladen
        $stellplatzIds = $proStellplatz->pluck('stellplatz_id');
        $stellplatzMap = \App\Models\Stellplatz::whereIn('id', $stellplatzIds)->get()->keyBy('id');

        // Jahresgesamtwerte
        $gesamt = Uebernachtung::whereYear('datum', $jahr)
            ->selectRaw('
                COUNT(*) as eintraege,
                SUM(anzahl_naechte) as naechte_gesamt,
                SUM(anzahl_personen) as personen_gesamt,
                SUM(anzahl_naechte * anzahl_personen) as personennaechte_gesamt
            ')
            ->first();

        $verfuegbareJahre = Uebernachtung::selectRaw('DISTINCT YEAR(datum) as jahr')
            ->orderByDesc('jahr')->pluck('jahr');

        $monate = [
            1=>'Januar', 2=>'Februar', 3=>'März', 4=>'April',
            5=>'Mai', 6=>'Juni', 7=>'Juli', 8=>'August',
            9=>'September', 10=>'Oktober', 11=>'November', 12=>'Dezember',
        ];

        return view('uebernachtungen.statistik', compact(
            'jahr', 'proMonat', 'proStellplatz', 'proPaechter',
            'paechterMap', 'stellplatzMap', 'gesamt',
            'verfuegbareJahre', 'monate'
        ));
    }

    // ── Kalender ──────────────────────────────────────────────────────────────

    public function kalender(Request $request)
    {
        $jahr  = (int) $request->get('jahr', now()->year);
        $monat = $request->get('monat') ? (int) $request->get('monat') : null;

        // Alle Übernachtungen holen, die in das Jahr (bzw. den Monat) fallen
        $von = $monat
            ? Carbon::create($jahr, $monat, 1)->startOfMonth()
            : Carbon::create($jahr, 1, 1);
        $bis = $monat
            ? Carbon::create($jahr, $monat, 1)->endOfMonth()
            : Carbon::create($jahr, 12, 31);

        $uebernachtungen = Uebernachtung::with(['vertrag.paechter', 'vertrag.stellplatz'])
            ->where('datum', '<=', $bis)
            ->where('abreisedatum', '>', $von)
            ->orderBy('datum')
            ->get();

        // Belegungs-Map aufbauen: 'Y-m-d' => [Uebernachtung, ...]
        // Ein Tag gilt als belegt von datum bis abreisedatum (exklusive)
        $belegungen = [];
        foreach ($uebernachtungen as $u) {
            $tag = $u->datum->copy();
            while ($tag->lt($u->abreisedatum)) {
                $key = $tag->format('Y-m-d');
                $belegungen[$key][] = $u;
                $tag->addDay();
            }
        }

        $monate = [
            1=>'Januar', 2=>'Februar', 3=>'März', 4=>'April',
            5=>'Mai', 6=>'Juni', 7=>'Juli', 8=>'August',
            9=>'September', 10=>'Oktober', 11=>'November', 12=>'Dezember',
        ];

        $verfuegbareJahre = Uebernachtung::selectRaw('DISTINCT YEAR(datum) as jahr')
            ->orderByDesc('jahr')->pluck('jahr');

        if ($verfuegbareJahre->isEmpty()) {
            $verfuegbareJahre = collect([now()->year]);
        }

        return view('uebernachtungen.kalender', compact(
            'jahr', 'monat', 'belegungen', 'monate', 'verfuegbareJahre'
        ));
    }
}
