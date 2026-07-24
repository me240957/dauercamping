@extends('layouts.app')
@section('title', 'Pächter bearbeiten')
@section('page-title', $paechter->voller_name . ' bearbeiten')
@section('header-actions')
    <a href="{{ route('paechter.show', $paechter) }}" class="text-sm text-gray-500 hover:text-gray-700">← Zurück</a>
@endsection
@section('content')
<div class="max-w-xl">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form method="POST" action="{{ route('paechter.update', $paechter) }}" class="space-y-4">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Vorname *</label>
                    <input type="text" name="vorname" value="{{ old('vorname', $paechter->vorname) }}" required
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nachname *</label>
                    <input type="text" name="nachname" value="{{ old('nachname', $paechter->nachname) }}" required
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">E-Mail</label>
                <input type="email" name="email" value="{{ old('email', $paechter->email) }}"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500 @error('email') border-red-500 @enderror">
                @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Telefon</label>
                    <input type="text" name="telefon" value="{{ old('telefon', $paechter->telefon) }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mobil</label>
                    <input type="text" name="mobil" value="{{ old('mobil', $paechter->mobil) }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Straße</label>
                <input type="text" name="strasse" value="{{ old('strasse', $paechter->strasse) }}"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">PLZ</label>
                    <input type="text" name="plz" value="{{ old('plz', $paechter->plz) }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ort</label>
                    <input type="text" name="ort" value="{{ old('ort', $paechter->ort) }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Geburtsdatum</label>
                    <input type="date" name="geburtsdatum" value="{{ old('geburtsdatum', $paechter->geburtsdatum?->format('Y-m-d')) }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                    <select name="status" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="aktiv" @selected(old('status', $paechter->status) === 'aktiv')>Aktiv</option>
                        <option value="inaktiv" @selected(old('status', $paechter->status) === 'inaktiv')>Inaktiv</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Notizen</label>
                <textarea name="notizen" rows="3"
                          class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">{{ old('notizen', $paechter->notizen) }}</textarea>
            </div>
            <div class="pt-2 flex gap-3">
                <button type="submit"
                        class="px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-md hover:bg-emerald-700 transition-colors">
                    Änderungen speichern
                </button>
                <a href="{{ route('paechter.show', $paechter) }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">Abbrechen</a>
            </div>
        </form>
    </div>
    <div class="mt-4 bg-white rounded-lg border border-red-200 p-4">
        <h3 class="text-sm font-semibold text-red-700 mb-2">Pächter löschen</h3>
        <p class="text-xs text-gray-500 mb-3">Nur möglich, wenn keine aktiven Verträge bestehen.</p>
        <form method="POST" action="{{ route('paechter.destroy', $paechter) }}"
              onsubmit="return confirm('Pächter {{ $paechter->voller_name }} wirklich löschen?')">
            @csrf @method('DELETE')
            <button type="submit" class="px-3 py-1.5 bg-red-600 text-white text-xs font-medium rounded-md hover:bg-red-700 transition-colors">Löschen</button>
        </form>
    </div>
</div>
@endsection
