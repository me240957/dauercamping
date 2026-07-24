@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- Stat Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">

    <x-stat-card
        label="Stellplätze gesamt"
        :value="$stats['stellplaetze_gesamt']"
        icon="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"
        color="bg-emerald-100" icon-color="text-emerald-600"
        :sub="$stats['stellplaetze_verpachtet'] . ' verpachtet'"
    />

    <x-stat-card
        label="Aktive Pächter"
        :value="$stats['paechter_aktiv']"
        icon="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"
        color="bg-blue-100" icon-color="text-blue-600"
    />

    <x-stat-card
        label="Offene Zahlungen"
        :value="$stats['zahlungen_offen']"
        icon="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
        color="bg-amber-100" icon-color="text-amber-600"
        :sub="number_format($stats['offener_betrag'], 2, ',', '.') . ' € ausstehend'"
    />

    <x-stat-card
        label="Einnahmen {{ now()->year }}"
        :value="number_format($stats['einnahmen_laufendes_jahr'], 2, ',', '.') . ' €'"
        icon="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
        color="bg-green-100" icon-color="text-green-600"
        sub="bezahlte Zahlungen im laufenden Jahr"
    />

</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Nächste fällige Zahlungen --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-900">Nächste fällige Zahlungen</h3>
            <a href="{{ route('zahlungen.index', ['status' => 'offen']) }}"
               class="text-xs text-emerald-600 hover:text-emerald-800 font-medium">Alle anzeigen →</a>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($naechste_zahlungen as $zahlung)
                <div class="px-5 py-3 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-900">
                            {{ $zahlung->vertrag->paechter->voller_name }}
                        </p>
                        <p class="text-xs text-gray-500">
                            Stellplatz {{ $zahlung->vertrag->stellplatz->nummer }} · {{ $zahlung->jahr }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold text-gray-900">
                            {{ number_format($zahlung->betrag, 2, ',', '.') }} €
                        </p>
                        @if($zahlung->faellig_am)
                            <p class="text-xs {{ $zahlung->ist_ueberfaellig ? 'text-red-600 font-medium' : 'text-gray-500' }}">
                                {{ $zahlung->faellig_am->format('d.m.Y') }}
                                @if($zahlung->ist_ueberfaellig) (überfällig) @endif
                            </p>
                        @endif
                    </div>
                </div>
            @empty
                <p class="px-5 py-6 text-sm text-gray-500 text-center">Keine offenen Zahlungen</p>
            @endforelse
        </div>
    </div>

    {{-- Letzte Verträge --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-900">Letzte Verträge</h3>
            <a href="{{ route('vertraege.index') }}"
               class="text-xs text-emerald-600 hover:text-emerald-800 font-medium">Alle anzeigen →</a>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($letzte_vertraege as $vertrag)
                <a href="{{ route('vertraege.show', $vertrag) }}"
                   class="px-5 py-3 flex items-center justify-between hover:bg-gray-50 transition-colors">
                    <div>
                        <p class="text-sm font-medium text-gray-900">
                            {{ $vertrag->paechter->voller_name }}
                        </p>
                        <p class="text-xs text-gray-500">
                            Stellplatz {{ $vertrag->stellplatz->nummer }} · ab {{ $vertrag->beginn->format('d.m.Y') }}
                        </p>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                        {{ $vertrag->status === 'aktiv' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                        {{ ucfirst($vertrag->status) }}
                    </span>
                </a>
            @empty
                <p class="px-5 py-6 text-sm text-gray-500 text-center">Noch keine Verträge vorhanden</p>
            @endforelse
        </div>
    </div>

</div>

@endsection
