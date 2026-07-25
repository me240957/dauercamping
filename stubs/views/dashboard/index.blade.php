@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('header-actions')
    <span class="text-sm text-gray-400">{{ now()->translatedFormat('l, d. F Y') }}</span>
@endsection

@section('content')

{{-- ── KPI-Karten ───────────────────────────────────────────────────────── --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    {{-- Stellplätze --}}
    <a href="{{ route('stellplaetze.index') }}"
       class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Stellplätze</p>
                <p class="mt-1 text-3xl font-bold text-gray-900">
                    {{ $stellplaetze_belegt }}<span class="text-lg font-normal text-gray-400">/{{ $stellplaetze_gesamt }}</span>
                </p>
                <p class="mt-1 text-xs text-gray-500">{{ $stellplaetze_frei }} frei</p>
            </div>
            <div class="h-10 w-10 rounded-lg bg-emerald-100 flex items-center justify-center flex-shrink-0">
                <svg class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                </svg>
            </div>
        </div>
        @php $belegung = $stellplaetze_gesamt > 0 ? round(($stellplaetze_belegt / $stellplaetze_gesamt) * 100) : 0; @endphp
        <div class="mt-3 bg-gray-100 rounded-full h-1.5">
            <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $belegung }}%"></div>
        </div>
        <p class="mt-1 text-xs text-gray-400">{{ $belegung }}% belegt</p>
    </a>

    {{-- Aktive Verträge --}}
    <a href="{{ route('vertraege.index') }}"
       class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Aktive Verträge</p>
                <p class="mt-1 text-3xl font-bold text-gray-900">{{ $vertraege_aktiv }}</p>
                @if($vertraege_auslaufend->count() > 0)
                    <p class="mt-1 text-xs text-amber-600 font-medium">{{ $vertraege_auslaufend->count() }} laufen bald aus</p>
                @else
                    <p class="mt-1 text-xs text-gray-500">Alle aktuell</p>
                @endif
            </div>
            <div class="h-10 w-10 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0">
                <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
        </div>
    </a>

    {{-- Offene Zahlungen --}}
    <a href="{{ route('zahlungen.index') }}"
       class="bg-white rounded-lg shadow-sm border p-5 hover:shadow-md transition-shadow
           {{ $zahlungen_ueberfaellig->count() > 0 ? 'border-red-300' : 'border-gray-200' }}">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Offene Zahlungen</p>
                <p class="mt-1 text-3xl font-bold {{ $zahlungen_ueberfaellig->count() > 0 ? 'text-red-600' : 'text-gray-900' }}">
                    {{ $zahlungen_offen }}
                </p>
                <p class="mt-1 text-xs {{ $zahlungen_ueberfaellig->count() > 0 ? 'text-red-500 font-medium' : 'text-gray-500' }}">
                    {{ number_format($offener_betrag, 2, ',', '.') }} €
                    @if($zahlungen_ueberfaellig->count() > 0)
                        · {{ $zahlungen_ueberfaellig->count() }} überfällig
                    @endif
                </p>
            </div>
            <div class="h-10 w-10 rounded-lg {{ $zahlungen_ueberfaellig->count() > 0 ? 'bg-red-100' : 'bg-amber-100' }} flex items-center justify-center flex-shrink-0">
                <svg class="h-5 w-5 {{ $zahlungen_ueberfaellig->count() > 0 ? 'text-red-600' : 'text-amber-600' }}"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </a>

    {{-- Einnahmen laufendes Jahr --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Einnahmen {{ now()->year }}</p>
                <p class="mt-1 text-3xl font-bold text-emerald-600">
                    {{ number_format($einnahmen_jahr, 0, ',', '.') }} €
                </p>
                <p class="mt-1 text-xs text-gray-500">bezahlte Zahlungen</p>
            </div>
            <div class="h-10 w-10 rounded-lg bg-emerald-100 flex items-center justify-center flex-shrink-0">
                <svg class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
        </div>
    </div>

</div>

{{-- ── Heute: An-/Abreisen & Anwesende ─────────────────────────────────── --}}
@if($anreisen_heute->count() > 0 || $abreisen_heute->count() > 0 || $aktuell_anwesend->count() > 0)
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">

    {{-- Aktuell anwesend --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-900">Aktuell anwesend</h3>
            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-800">
                {{ $aktuell_anwesend->count() }} Gruppen
            </span>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($aktuell_anwesend as $u)
                <div class="px-5 py-3 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $u->vertrag?->paechter?->voller_name ?? '–' }}</p>
                        <p class="text-xs text-gray-400">Platz {{ $u->vertrag?->stellplatz?->nummer ?? '?' }} · {{ $u->anzahl_personen }} Pers.</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-400">Abreise</p>
                        <p class="text-xs font-semibold text-gray-700">{{ $u->abreisedatum?->format('d.m.') }}</p>
                    </div>
                </div>
            @empty
                <p class="px-5 py-6 text-sm text-gray-400 text-center">Niemand anwesend</p>
            @endforelse
        </div>
    </div>

    {{-- Anreisen heute --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-900">Anreisen heute</h3>
            @if($anreisen_heute->count() > 0)
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                    {{ $anreisen_heute->count() }}
                </span>
            @endif
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($anreisen_heute as $u)
                <div class="px-5 py-3">
                    <p class="text-sm font-medium text-gray-900">{{ $u->vertrag?->paechter?->voller_name ?? '–' }}</p>
                    <p class="text-xs text-gray-400">Platz {{ $u->vertrag?->stellplatz?->nummer ?? '?' }} · {{ $u->anzahl_personen }} Pers. · {{ $u->anzahl_naechte }} Nächte</p>
                </div>
            @empty
                <p class="px-5 py-6 text-sm text-gray-400 text-center">Keine Anreisen</p>
            @endforelse
        </div>
    </div>

    {{-- Abreisen heute --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-900">Abreisen heute</h3>
            @if($abreisen_heute->count() > 0)
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700">
                    {{ $abreisen_heute->count() }}
                </span>
            @endif
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($abreisen_heute as $u)
                <div class="px-5 py-3">
                    <p class="text-sm font-medium text-gray-900">{{ $u->vertrag?->paechter?->voller_name ?? '–' }}</p>
                    <p class="text-xs text-gray-400">Platz {{ $u->vertrag?->stellplatz?->nummer ?? '?' }} · {{ $u->anzahl_personen }} Pers.</p>
                </div>
            @empty
                <p class="px-5 py-6 text-sm text-gray-400 text-center">Keine Abreisen</p>
            @endforelse
        </div>
    </div>

</div>
@endif

{{-- ── Warnungen & Listen ───────────────────────────────────────────────── --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

    {{-- Überfällige Zahlungen --}}
    @if($zahlungen_ueberfaellig->count() > 0)
    <div class="bg-white rounded-lg shadow-sm border border-red-200">
        <div class="px-5 py-4 border-b border-red-100 flex items-center gap-2">
            <svg class="h-4 w-4 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <h3 class="text-sm font-semibold text-red-700">Überfällige Zahlungen ({{ $zahlungen_ueberfaellig->count() }})</h3>
        </div>
        <div class="divide-y divide-gray-100">
            @foreach($zahlungen_ueberfaellig as $z)
                <div class="px-5 py-3 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $z->vertrag?->paechter?->voller_name ?? '–' }}</p>
                        <p class="text-xs text-gray-400">Platz {{ $z->vertrag?->stellplatz?->nummer ?? '?' }} · fällig {{ $z->faellig_am?->format('d.m.Y') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-red-600">{{ number_format($z->betrag, 2, ',', '.') }} €</p>
                        <p class="text-xs text-red-400">{{ $z->faellig_am?->diffForHumans() }}</p>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="px-5 py-3 border-t border-red-100 bg-red-50">
            <a href="{{ route('zahlungen.index') }}" class="text-xs font-medium text-red-600 hover:text-red-800">Alle Zahlungen →</a>
        </div>
    </div>
    @endif

    {{-- Nächste fällige Zahlungen --}}
    @if($naechste_zahlungen->count() > 0)
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-5 py-4 border-b border-gray-200">
            <h3 class="text-sm font-semibold text-gray-900">Nächste fällige Zahlungen</h3>
        </div>
        <div class="divide-y divide-gray-100">
            @foreach($naechste_zahlungen as $z)
                <div class="px-5 py-3 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $z->vertrag?->paechter?->voller_name ?? '–' }}</p>
                        <p class="text-xs text-gray-400">Platz {{ $z->vertrag?->stellplatz?->nummer ?? '?' }} · {{ $z->faellig_am?->format('d.m.Y') }}</p>
                    </div>
                    <p class="text-sm font-semibold text-gray-900">{{ number_format($z->betrag, 2, ',', '.') }} €</p>
                </div>
            @endforeach
        </div>
        <div class="px-5 py-3 border-t border-gray-100">
            <a href="{{ route('zahlungen.index') }}" class="text-xs font-medium text-emerald-600 hover:text-emerald-800">Alle Zahlungen →</a>
        </div>
    </div>
    @endif

    {{-- Verträge laufen bald aus --}}
    @if($vertraege_auslaufend->count() > 0)
    <div class="bg-white rounded-lg shadow-sm border border-amber-200">
        <div class="px-5 py-4 border-b border-amber-100 flex items-center gap-2">
            <svg class="h-4 w-4 text-amber-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h3 class="text-sm font-semibold text-amber-700">Verträge laufen bald aus</h3>
        </div>
        <div class="divide-y divide-gray-100">
            @foreach($vertraege_auslaufend as $v)
                <a href="{{ route('vertraege.show', $v) }}"
                   class="px-5 py-3 flex items-center justify-between hover:bg-amber-50 transition-colors">
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $v->paechter?->voller_name ?? '–' }}</p>
                        <p class="text-xs text-gray-400">Stellplatz {{ $v->stellplatz?->nummer ?? '?' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold text-amber-700">{{ $v->ende?->format('d.m.Y') }}</p>
                        <p class="text-xs text-amber-500">{{ $v->ende?->diffForHumans() }}</p>
                    </div>
                </a>
            @endforeach
        </div>
        <div class="px-5 py-3 border-t border-amber-100 bg-amber-50">
            <a href="{{ route('vertraege.index') }}" class="text-xs font-medium text-amber-700 hover:text-amber-900">Alle Verträge →</a>
        </div>
    </div>
    @endif

</div>

@endsection
