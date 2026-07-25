@extends('layouts.app')
@section('title', $dokument->titel)
@section('page-title', $dokument->titel)

@section('header-actions')
    <a href="{{ $dokument->download_url }}"
       class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-md shadow-sm transition-colors">
        <svg class="mr-2 -ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
        </svg>
        Download
    </a>
    <a href="{{ route('dokumente.edit', $dokument) }}"
       class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-md shadow-sm transition-colors">
        Bearbeiten
    </a>
    <a href="{{ route('dokumente.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Übersicht</a>
@endsection

@section('content')

<div class="max-w-2xl space-y-4">

    {{-- Dokument-Details --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 divide-y divide-gray-100">

        {{-- Datei-Header --}}
        <div class="px-6 py-4 flex items-center gap-4">
            @php
                $iconColor = match($dokument->datei_icon) {
                    'pdf'   => 'text-red-500',
                    'image' => 'text-blue-500',
                    'word'  => 'text-blue-700',
                    'excel' => 'text-green-600',
                    default => 'text-gray-400',
                };
                $badgeClass = match($dokument->kategorie_badge) {
                    'blue'  => 'bg-blue-100 text-blue-800',
                    'amber' => 'bg-amber-100 text-amber-800',
                    'green' => 'bg-green-100 text-green-800',
                    default => 'bg-gray-100 text-gray-700',
                };
            @endphp
            <svg class="h-12 w-12 flex-shrink-0 {{ $iconColor }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
            <div>
                <h2 class="text-base font-semibold text-gray-900">{{ $dokument->titel }}</h2>
                <p class="text-sm text-gray-500 mt-0.5">{{ $dokument->dateiname }}</p>
                <span class="inline-flex items-center mt-1 px-2 py-0.5 rounded text-xs font-medium {{ $badgeClass }}">
                    {{ $dokument->kategorie_label }}
                </span>
            </div>
        </div>

        {{-- Metadaten --}}
        <dl class="divide-y divide-gray-50">
            <div class="px-6 py-3 flex justify-between text-sm">
                <dt class="text-gray-500 w-40">Dateigröße</dt>
                <dd class="text-gray-900 font-medium">{{ $dokument->dateigroesse_formattert }}</dd>
            </div>
            <div class="px-6 py-3 flex justify-between text-sm">
                <dt class="text-gray-500 w-40">Dateityp</dt>
                <dd class="text-gray-900">{{ $dokument->dateityp }}</dd>
            </div>
            <div class="px-6 py-3 flex justify-between text-sm">
                <dt class="text-gray-500 w-40">Dokumentdatum</dt>
                <dd class="text-gray-900">{{ $dokument->dokument_datum?->format('d.m.Y') ?? '–' }}</dd>
            </div>
            <div class="px-6 py-3 flex justify-between text-sm">
                <dt class="text-gray-500 w-40">Hochgeladen am</dt>
                <dd class="text-gray-900">{{ $dokument->created_at->format('d.m.Y H:i') }} Uhr</dd>
            </div>
            @if($dokument->paechter)
                <div class="px-6 py-3 flex justify-between text-sm">
                    <dt class="text-gray-500 w-40">Pächter</dt>
                    <dd class="text-gray-900">
                        <a href="{{ route('paechter.show', $dokument->paechter) }}"
                           class="text-emerald-600 hover:underline">{{ $dokument->paechter->voller_name }}</a>
                    </dd>
                </div>
            @endif
            @if($dokument->vertrag)
                <div class="px-6 py-3 flex justify-between text-sm">
                    <dt class="text-gray-500 w-40">Vertrag</dt>
                    <dd class="text-gray-900">
                        <a href="{{ route('vertraege.show', $dokument->vertrag) }}"
                           class="text-emerald-600 hover:underline">
                            Vertrag #{{ $dokument->vertrag_id }}
                            @if($dokument->vertrag->stellplatz)
                                – Stellplatz {{ $dokument->vertrag->stellplatz->nummer }}
                            @endif
                        </a>
                    </dd>
                </div>
            @endif
            @if($dokument->beschreibung)
                <div class="px-6 py-3 text-sm">
                    <dt class="text-gray-500 mb-1">Beschreibung</dt>
                    <dd class="text-gray-900 whitespace-pre-wrap">{{ $dokument->beschreibung }}</dd>
                </div>
            @endif
        </dl>
    </div>

    {{-- Löschen --}}
    <div class="bg-white rounded-lg shadow-sm border border-red-200 p-4">
        <h3 class="text-sm font-medium text-red-700 mb-2">Dokument löschen</h3>
        <p class="text-xs text-gray-500 mb-3">Das Dokument und die Datei werden dauerhaft gelöscht. Diese Aktion kann nicht rückgängig gemacht werden.</p>
        <form method="POST" action="{{ route('dokumente.destroy', $dokument) }}"
              onsubmit="return confirm('Dokument „{{ addslashes($dokument->titel) }}" wirklich löschen?')">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-md transition-colors">
                Dokument löschen
            </button>
        </form>
    </div>

</div>

@endsection
