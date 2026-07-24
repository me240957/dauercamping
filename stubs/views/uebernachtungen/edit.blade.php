@extends('layouts.app')
@section('title', 'Übernachtung bearbeiten')
@section('page-title', 'Übernachtung bearbeiten')
@section('header-actions')
    <a href="{{ route('uebernachtungen.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Zurück</a>
@endsection
@section('content')
<div class="max-w-lg">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form method="POST" action="{{ route('uebernachtungen.update', $uebernachtung) }}" class="space-y-4">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Vertrag / Pächter <span class="text-red-500">*</span></label>
                <select name="vertrag_id" required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                    @foreach($vertraege as $v)
                        <option value="{{ $v->id }}" @selected(old('vertrag_id', $uebernachtung->vertrag_id) == $v->id)>
                            {{ $v->paechter->voller_name }} – Stellplatz {{ $v->stellplatz->nummer }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Anreisedatum <span class="text-red-500">*</span></label>
                    <input type="date" id="datum" name="datum"
                           value="{{ old('datum', $uebernachtung->datum->format('Y-m-d')) }}" required
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Abreisedatum <span class="text-red-500">*</span></label>
                    <input type="date" id="abreisedatum" name="abreisedatum"
                           value="{{ old('abreisedatum', $uebernachtung->abreisedatum?->format('Y-m-d')) }}" required
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                </div>
            </div>

            {{-- Live-Anzeige der berechneten Nächte --}}
            <div id="naechte-anzeige" class="hidden bg-emerald-50 border border-emerald-200 rounded-md px-4 py-3 flex items-center gap-3">
                <svg class="h-5 w-5 text-emerald-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
                <span class="text-sm text-emerald-800">
                    Aufenthalt: <strong id="naechte-zahl" class="font-bold">0</strong> Nacht/Nächte
                    <span class="text-emerald-600 ml-1">(bisher: {{ $uebernachtung->anzahl_naechte }})</span>
                </span>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Anzahl Personen <span class="text-red-500">*</span></label>
                <input type="number" name="anzahl_personen"
                       value="{{ old('anzahl_personen', $uebernachtung->anzahl_personen) }}"
                       min="1" max="50" required
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Notizen</label>
                <textarea name="notizen" rows="3"
                          class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">{{ old('notizen', $uebernachtung->notizen) }}</textarea>
            </div>

            <div class="pt-2 flex gap-3">
                <button type="submit"
                        class="px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-md hover:bg-emerald-700 transition-colors">
                    Speichern
                </button>
                <a href="{{ route('uebernachtungen.index') }}"
                   class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">Abbrechen</a>
            </div>
        </form>
    </div>

    <div class="mt-4 bg-white rounded-lg border border-red-200 p-4">
        <h3 class="text-sm font-semibold text-red-700 mb-2">Eintrag löschen</h3>
        <form method="POST" action="{{ route('uebernachtungen.destroy', $uebernachtung) }}"
              onsubmit="return confirm('Eintrag wirklich löschen?')">
            @csrf @method('DELETE')
            <button type="submit"
                    class="px-3 py-1.5 bg-red-600 text-white text-xs font-medium rounded-md hover:bg-red-700 transition-colors">
                Löschen
            </button>
        </form>
    </div>
</div>

<script>
    function berechneNaechte() {
        const anreise = document.getElementById('datum').value;
        const abreise = document.getElementById('abreisedatum').value;
        const anzeige = document.getElementById('naechte-anzeige');
        const zahl    = document.getElementById('naechte-zahl');

        if (anreise && abreise && abreise > anreise) {
            const diff = (new Date(abreise) - new Date(anreise)) / (1000 * 60 * 60 * 24);
            zahl.textContent = diff;
            anzeige.classList.remove('hidden');
        } else {
            anzeige.classList.add('hidden');
        }
    }

    document.getElementById('datum').addEventListener('change', berechneNaechte);
    document.getElementById('abreisedatum').addEventListener('change', berechneNaechte);
    berechneNaechte();
</script>
@endsection
