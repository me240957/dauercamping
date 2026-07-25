@extends('layouts.app')
@section('title', 'Dokumente')
@section('page-title', 'Dokumente')

@section('header-actions')
    <a href="{{ route('dokumente.create') }}"
       class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-md shadow-sm transition-colors">
        <svg class="mr-2 -ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Dokument hochladen
    </a>
@endsection

@section('content')

{{-- Filter --}}
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
    <form method="GET" action="{{ route('dokumente.index') }}" class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-xs font-medium text-gray-700 mb-1">Kategorie</label>
            <select name="kategorie" onchange="this.form.submit()"
                    class="border border-gray-300 rounded-md text-sm px-3 py-1.5 focus:ring-emerald-500 focus:border-emerald-500">
                <option value="">Alle Kategorien</option>
                <option value="angebot"   @selected(request('kategorie') === 'angebot')>Angebot</option>
                <option value="rechnung"  @selected(request('kategorie') === 'rechnung')>Rechnung</option>
                <option value="zahlung"   @selected(request('kategorie') === 'zahlung')>Zahlung</option>
                <option value="sonstiges" @selected(request('kategorie') === 'sonstiges')>Sonstiges</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-700 mb-1">Pächter</label>
            <select name="paechter_id" onchange="this.form.submit()"
                    class="border border-gray-300 rounded-md text-sm px-3 py-1.5 focus:ring-emerald-500 focus:border-emerald-500">
                <option value="">Alle Pächter</option>
                @foreach($paechter as $p)
                    <option value="{{ $p->id }}" @selected(request('paechter_id') == $p->id)>
                        {{ $p->voller_name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="flex-1 min-w-48">
            <label class="block text-xs font-medium text-gray-700 mb-1">Suche</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Titel, Dateiname …"
                   class="w-full border border-gray-300 rounded-md text-sm px-3 py-1.5 focus:ring-emerald-500 focus:border-emerald-500">
        </div>
        <button type="submit"
                class="px-3 py-1.5 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-md transition-colors">
            Suchen
        </button>
        @if(request('kategorie') || request('search') || request('paechter_id'))
            <a href="{{ route('dokumente.index') }}"
               class="px-3 py-1.5 text-sm text-gray-600 hover:text-gray-900 border border-gray-300 rounded-md transition-colors">
                Zurücksetzen
            </a>
        @endif
    </form>
</div>

{{-- Tabelle --}}
<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Dokument</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kategorie</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pächter / Vertrag</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Datum</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Größe</th>
                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aktionen</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($dokumente as $dok)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            {{-- Datei-Icon --}}
                            @php
                                $iconColor = match($dok->datei_icon) {
                                    'pdf'   => 'text-red-500',
                                    'image' => 'text-blue-500',
                                    'word'  => 'text-blue-700',
                                    'excel' => 'text-green-600',
                                    default => 'text-gray-400',
                                };
                            @endphp
                            <svg class="h-8 w-8 flex-shrink-0 {{ $iconColor }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $dok->titel }}</p>
                                <p class="text-xs text-gray-400">{{ $dok->dateiname }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        @php
                            $badgeClass = match($dok->kategorie_badge) {
                                'blue'  => 'bg-blue-100 text-blue-800',
                                'amber' => 'bg-amber-100 text-amber-800',
                                'green' => 'bg-green-100 text-green-800',
                                default => 'bg-gray-100 text-gray-700',
                            };
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $badgeClass }}">
                            {{ $dok->kategorie_label }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-700">
                        @if($dok->paechter)
                            <p class="font-medium">{{ $dok->paechter->voller_name }}</p>
                        @endif
                        @if($dok->vertrag)
                            <p class="text-xs text-gray-400">Vertrag #{{ $dok->vertrag_id }}</p>
                        @endif
                        @if(!$dok->paechter && !$dok->vertrag)
                            <span class="text-gray-300">–</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-700">
                        {{ $dok->dokument_datum?->format('d.m.Y') ?? '–' }}
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-500">
                        {{ $dok->dateigroesse_formattert }}
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ $dok->download_url }}"
                               class="text-xs font-medium text-emerald-600 hover:text-emerald-800 transition-colors">
                                Download
                            </a>
                            <a href="{{ route('dokumente.show', $dok) }}"
                               class="text-xs font-medium text-gray-500 hover:text-gray-700 transition-colors">
                                Details
                            </a>
                            <a href="{{ route('dokumente.edit', $dok) }}"
                               class="text-xs font-medium text-blue-600 hover:text-blue-800 transition-colors">
                                Bearbeiten
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-12 text-center text-sm text-gray-500">
                        Keine Dokumente gefunden.
                        <a href="{{ route('dokumente.create') }}" class="text-emerald-600 hover:underline ml-1">Jetzt hochladen</a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if($dokumente->hasPages())
        <div class="px-4 py-3 border-t border-gray-200">
            {{ $dokumente->links() }}
        </div>
    @endif
</div>

@endsection
