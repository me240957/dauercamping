@extends('layouts.app')
@section('title', 'Kalender ' . $jahr)
@section('page-title', $monat ? $monate[$monat] . ' ' . $jahr : 'Jahreskalender ' . $jahr)

@section('header-actions')
    <form method="GET" action="{{ route('uebernachtungen.kalender') }}" class="flex items-center gap-2">
        {{-- Jahresauswahl --}}
        <select name="jahr" onchange="this.form.submit()"
                class="border border-gray-300 rounded-md text-sm px-3 py-1.5 focus:ring-emerald-500 focus:border-emerald-500">
            @foreach($verfuegbareJahre as $j)
                <option value="{{ $j }}" @selected($j == $jahr)>{{ $j }}</option>
            @endforeach
        </select>

        {{-- Monatsauswahl --}}
        <select name="monat" onchange="this.form.submit()"
                class="border border-gray-300 rounded-md text-sm px-3 py-1.5 focus:ring-emerald-500 focus:border-emerald-500">
            <option value="">Alle Monate</option>
            @foreach($monate as $nr => $name)
                <option value="{{ $nr }}" @selected($monat == $nr)>{{ $name }}</option>
            @endforeach
        </select>

        @if($monat)
            <a href="{{ route('uebernachtungen.kalender', ['jahr' => $jahr]) }}"
               class="text-sm text-gray-500 hover:text-gray-700">Jahresansicht</a>
        @endif
    </form>
@endsection

@section('content')

@php
    $tagNamen = ['Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa', 'So'];
    $heute    = now()->format('Y-m-d');

    // Farben für verschiedene Stellplätze (rotierend)
    $farben = [
        'bg-emerald-100 border-emerald-400 text-emerald-900',
        'bg-blue-100 border-blue-400 text-blue-900',
        'bg-purple-100 border-purple-400 text-purple-900',
        'bg-amber-100 border-amber-400 text-amber-900',
        'bg-rose-100 border-rose-400 text-rose-900',
        'bg-cyan-100 border-cyan-400 text-cyan-900',
        'bg-indigo-100 border-indigo-400 text-indigo-900',
        'bg-teal-100 border-teal-400 text-teal-900',
    ];

    // Stellplatz-ID → Farb-Index (konsistent)
    $stellplatzFarben = [];
    $farbIndex = 0;
@endphp

@if($monat)
    {{-- ── Detailansicht: ein Monat groß ──────────────────────────────── --}}
    @php
        $ersterTag = \Carbon\Carbon::create($jahr, $monat, 1);
        $letzterTag = $ersterTag->copy()->endOfMonth();
        // Wochentag des 1. (0=Mo … 6=So)
        $startWochentag = ($ersterTag->dayOfWeek + 6) % 7;
    @endphp

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        {{-- Kopfzeile Wochentage --}}
        <div class="grid grid-cols-7 border-b border-gray-200">
            @foreach($tagNamen as $tn)
                <div class="py-2 text-center text-xs font-semibold text-gray-500 {{ $tn === 'Sa' || $tn === 'So' ? 'bg-gray-50' : '' }}">
                    {{ $tn }}
                </div>
            @endforeach
        </div>

        {{-- Kalender-Grid --}}
        <div class="grid grid-cols-7">
            {{-- Leere Zellen vor dem 1. --}}
            @for($i = 0; $i < $startWochentag; $i++)
                <div class="min-h-24 border-b border-r border-gray-100 bg-gray-50"></div>
            @endfor

            @for($tag = 1; $tag <= $letzterTag->day; $tag++)
                @php
                    $datum = sprintf('%04d-%02d-%02d', $jahr, $monat, $tag);
                    $eintraege = $belegungen[$datum] ?? [];
                    $wochentag = (\Carbon\Carbon::create($jahr, $monat, $tag)->dayOfWeek + 6) % 7;
                    $istWochenende = $wochentag >= 5;
                    $istHeute = $datum === $heute;
                @endphp
                <div class="min-h-24 border-b border-r border-gray-100 p-1
                    {{ $istWochenende ? 'bg-gray-50' : 'bg-white' }}
                    {{ $istHeute ? 'ring-2 ring-inset ring-emerald-400' : '' }}">

                    {{-- Tageszahl --}}
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-xs font-semibold {{ $istHeute ? 'bg-emerald-600 text-white rounded-full w-5 h-5 flex items-center justify-center' : 'text-gray-700' }}">
                            {{ $tag }}
                        </span>
                    </div>

                    {{-- Belegungen --}}
                    @foreach($eintraege as $u)
                        @php
                            $spId = $u->vertrag->stellplatz_id ?? 0;
                            if (!isset($stellplatzFarben[$spId])) {
                                $stellplatzFarben[$spId] = $farben[$farbIndex % count($farben)];
                                $farbIndex++;
                            }
                            $farbe = $stellplatzFarben[$spId];
                            $istAnreise  = $u->datum->format('Y-m-d') === $datum;
                            $istAbreise  = $u->abreisedatum->format('Y-m-d') === \Carbon\Carbon::parse($datum)->addDay()->format('Y-m-d');
                        @endphp
                        <div class="text-xs px-1 py-0.5 rounded border-l-2 mb-0.5 truncate {{ $farbe }}"
                             title="{{ $u->vertrag->paechter->voller_name }} – Stellplatz {{ $u->vertrag->stellplatz->nummer }} ({{ $u->anzahl_personen }} Pers.)">
                            @if($istAnreise)▶ @endif
                            <span class="font-semibold">{{ $u->vertrag->stellplatz->nummer }}</span>
                            {{ $u->vertrag->paechter->nachname }}
                            @if($istAbreise)◀@endif
                        </div>
                    @endforeach
                </div>
            @endfor

            {{-- Leere Zellen nach dem letzten Tag --}}
            @php $restZellen = (7 - (($startWochentag + $letzterTag->day) % 7)) % 7; @endphp
            @for($i = 0; $i < $restZellen; $i++)
                <div class="min-h-24 border-b border-r border-gray-100 bg-gray-50"></div>
            @endfor
        </div>
    </div>

    {{-- Legende --}}
    @if(!empty($stellplatzFarben))
    <div class="mt-4 bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <p class="text-xs font-semibold text-gray-500 mb-2">Legende</p>
        <div class="flex flex-wrap gap-2">
            @foreach($stellplatzFarben as $spId => $farbe)
                @php $sp = \App\Models\Stellplatz::find($spId); @endphp
                @if($sp)
                    <span class="inline-flex items-center px-2 py-1 rounded border-l-2 text-xs {{ $farbe }}">
                        Stellplatz {{ $sp->nummer }}
                    </span>
                @endif
            @endforeach
        </div>
    </div>
    @endif

@else
    {{-- ── Jahresübersicht: 12 Mini-Kalender ──────────────────────────── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @foreach($monate as $mNr => $mName)
            @php
                $erster     = \Carbon\Carbon::create($jahr, $mNr, 1);
                $letzter    = $erster->copy()->endOfMonth();
                $startWT    = ($erster->dayOfWeek + 6) % 7;
                // Gibt es in diesem Monat Belegungen?
                $hatBelegungen = false;
                for ($t = 1; $t <= $letzter->day; $t++) {
                    if (isset($belegungen[sprintf('%04d-%02d-%02d', $jahr, $mNr, $t)])) {
                        $hatBelegungen = true;
                        break;
                    }
                }
            @endphp
            <a href="{{ route('uebernachtungen.kalender', ['jahr' => $jahr, 'monat' => $mNr]) }}"
               class="bg-white rounded-lg shadow-sm border {{ $hatBelegungen ? 'border-emerald-300' : 'border-gray-200' }} p-3 hover:shadow-md transition-shadow">

                {{-- Monatsname --}}
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-semibold text-gray-900">{{ $mName }}</h3>
                    @if($hatBelegungen)
                        <span class="text-xs text-emerald-600 font-medium">
                            @php
                                $anzahl = 0;
                                for ($t = 1; $t <= $letzter->day; $t++) {
                                    $key = sprintf('%04d-%02d-%02d', $jahr, $mNr, $t);
                                    if (isset($belegungen[$key])) $anzahl += count($belegungen[$key]);
                                }
                            @endphp
                            {{ $anzahl }}×
                        </span>
                    @endif
                </div>

                {{-- Mini-Kalender --}}
                <div class="grid grid-cols-7 gap-px">
                    @foreach(['M','D','M','D','F','S','S'] as $ktn)
                        <div class="text-center text-gray-400" style="font-size:9px">{{ $ktn }}</div>
                    @endforeach

                    @for($i = 0; $i < $startWT; $i++)
                        <div></div>
                    @endfor

                    @for($t = 1; $t <= $letzter->day; $t++)
                        @php
                            $datumKey = sprintf('%04d-%02d-%02d', $jahr, $mNr, $t);
                            $belegt   = isset($belegungen[$datumKey]);
                            $istHeuteTag = $datumKey === $heute;
                        @endphp
                        <div class="text-center rounded"
                             style="font-size:10px; line-height:1.6;"
                             title="{{ $belegt ? implode(', ', array_map(fn($u) => $u->vertrag->stellplatz->nummer, $belegungen[$datumKey])) : '' }}">
                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full
                                {{ $istHeuteTag ? 'bg-emerald-600 text-white font-bold' : ($belegt ? 'bg-emerald-200 text-emerald-900 font-semibold' : 'text-gray-500') }}">
                                {{ $t }}
                            </span>
                        </div>
                    @endfor
                </div>
            </a>
        @endforeach
    </div>

    {{-- Jahresübersicht: Gesamtstatistik --}}
    @php
        $gesamtBelegt = count(array_filter(array_keys($belegungen), fn($d) => str_starts_with($d, $jahr)));
        $gesamtNaechte = array_sum(array_map(fn($u) => $u->anzahl_naechte,
            array_merge(...array_values($belegungen ?: [[]]))));
    @endphp
    <div class="mt-4 bg-white rounded-lg shadow-sm border border-gray-200 p-4 flex gap-6 text-sm text-gray-600">
        <span>
            <strong class="text-gray-900 font-semibold">{{ count($belegungen) }}</strong>
            belegte Tage in {{ $jahr }}
        </span>
        <span class="text-gray-300">|</span>
        <span>Auf Detail klicken, um einen Monat groß zu öffnen</span>
    </div>
@endif

@endsection
