@extends('layouts.app')
@section('title', 'Dokument bearbeiten')
@section('page-title', 'Dokument bearbeiten')

@section('header-actions')
    <a href="{{ route('dokumente.show', $dokument) }}" class="text-sm text-gray-500 hover:text-gray-700">← Details</a>
@endsection

@section('content')

<div class="max-w-2xl">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form method="POST" action="{{ route('dokumente.update', $dokument) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Titel --}}
            <div>
                <label for="titel" class="block text-sm font-medium text-gray-700 mb-1">Titel <span class="text-red-500">*</span></label>
                <input type="text" id="titel" name="titel" value="{{ old('titel', $dokument->titel) }}" required
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500 @error('titel') border-red-300 @enderror">
                @error('titel')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Kategorie --}}
            <div>
                <label for="kategorie" class="block text-sm font-medium text-gray-700 mb-1">Kategorie <span class="text-red-500">*</span></label>
                <select id="kategorie" name="kategorie" required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="angebot"   @selected(old('kategorie', $dokument->kategorie) === 'angebot')>Angebot</option>
                    <option value="rechnung"  @selected(old('kategorie', $dokument->kategorie) === 'rechnung')>Rechnung</option>
                    <option value="zahlung"   @selected(old('kategorie', $dokument->kategorie) === 'zahlung')>Zahlung</option>
                    <option value="sonstiges" @selected(old('kategorie', $dokument->kategorie) === 'sonstiges')>Sonstiges</option>
                </select>
            </div>

            {{-- Aktuelle Datei --}}
            <div class="rounded-md bg-gray-50 border border-gray-200 p-3 flex items-center gap-3">
                <svg class="h-6 w-6 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ $dokument->dateiname }}</p>
                    <p class="text-xs text-gray-400">{{ $dokument->dateigroesse_formattert }} · {{ $dokument->dateityp }}</p>
                </div>
                <a href="{{ $dokument->download_url }}" class="text-xs text-emerald-600 hover:underline flex-shrink-0">Download</a>
            </div>

            {{-- Neue Datei (optional) --}}
            <div>
                <label for="datei" class="block text-sm font-medium text-gray-700 mb-1">Neue Datei <span class="text-gray-400 font-normal">(optional – ersetzt die aktuelle)</span></label>
                <input type="file" id="datei" name="datei"
                       accept=".pdf,.jpg,.jpeg,.png,.gif,.webp,.doc,.docx,.xls,.xlsx"
                       class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 @error('datei') border border-red-300 rounded-md @enderror">
                @error('datei')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Dokumentdatum --}}
            <div>
                <label for="dokument_datum" class="block text-sm font-medium text-gray-700 mb-1">Dokumentdatum</label>
                <input type="date" id="dokument_datum" name="dokument_datum"
                       value="{{ old('dokument_datum', $dokument->dokument_datum?->format('Y-m-d')) }}"
                       class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
            </div>

            {{-- Pächter --}}
            <div>
                <label for="paechter_id" class="block text-sm font-medium text-gray-700 mb-1">Pächter <span class="text-gray-400 font-normal">(optional)</span></label>
                <select id="paechter_id" name="paechter_id"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">Kein Pächter</option>
                    @foreach($paechter as $p)
                        <option value="{{ $p->id }}" @selected(old('paechter_id', $dokument->paechter_id) == $p->id)>
                            {{ $p->voller_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Vertrag --}}
            <div>
                <label for="vertrag_id" class="block text-sm font-medium text-gray-700 mb-1">Vertrag <span class="text-gray-400 font-normal">(optional)</span></label>
                <select id="vertrag_id" name="vertrag_id"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">Kein Vertrag</option>
                    @foreach($vertraege as $v)
                        <option value="{{ $v->id }}" @selected(old('vertrag_id', $dokument->vertrag_id) == $v->id)>
                            {{ $v->paechter?->voller_name ?? '–' }} – Stellplatz {{ $v->stellplatz?->nummer ?? '?' }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Beschreibung --}}
            <div>
                <label for="beschreibung" class="block text-sm font-medium text-gray-700 mb-1">Beschreibung <span class="text-gray-400 font-normal">(optional)</span></label>
                <textarea id="beschreibung" name="beschreibung" rows="3"
                          class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">{{ old('beschreibung', $dokument->beschreibung) }}</textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="px-5 py-2 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-md shadow-sm transition-colors">
                    Speichern
                </button>
                <a href="{{ route('dokumente.show', $dokument) }}"
                   class="px-5 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-md shadow-sm transition-colors">
                    Abbrechen
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
