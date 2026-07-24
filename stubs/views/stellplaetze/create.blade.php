@extends('layouts.app')

@section('title', 'Stellplatz anlegen')
@section('page-title', 'Stellplatz anlegen')

@section('header-actions')
    <a href="{{ route('stellplaetze.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Zurück</a>
@endsection

@section('content')
<div class="max-w-xl">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form method="POST" action="{{ route('stellplaetze.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nummer <span class="text-red-500">*</span></label>
                <input type="text" name="nummer" value="{{ old('nummer') }}" required
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500 @error('nummer') border-red-500 @enderror"
                       placeholder="z.B. A-01">
                @error('nummer') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bezeichnung</label>
                <input type="text" name="bezeichnung" value="{{ old('bezeichnung') }}"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Größe (m²)</label>
                    <input type="number" name="groesse_qm" value="{{ old('groesse_qm') }}" step="0.01" min="0"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lage / Bereich</label>
                    <input type="text" name="lage" value="{{ old('lage') }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                <select name="status" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="aktiv" @selected(old('status', 'aktiv') === 'aktiv')>Aktiv</option>
                    <option value="inaktiv" @selected(old('status') === 'inaktiv')>Inaktiv</option>
                    <option value="gesperrt" @selected(old('status') === 'gesperrt')>Gesperrt</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Notizen</label>
                <textarea name="notizen" rows="3"
                          class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">{{ old('notizen') }}</textarea>
            </div>

            <div class="pt-2 flex gap-3">
                <button type="submit"
                        class="px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-md hover:bg-emerald-700 transition-colors">
                    Stellplatz anlegen
                </button>
                <a href="{{ route('stellplaetze.index') }}"
                   class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">Abbrechen</a>
            </div>
        </form>
    </div>
</div>
@endsection
