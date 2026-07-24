@extends('layouts.app')
@section('title', 'Pächter anlegen')
@section('page-title', 'Pächter anlegen')
@section('header-actions')
    <a href="{{ route('paechter.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Zurück</a>
@endsection
@section('content')
<div class="max-w-xl">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form method="POST" action="{{ route('paechter.store') }}" class="space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Vorname <span class="text-red-500">*</span></label>
                    <input type="text" name="vorname" value="{{ old('vorname') }}" required
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500 @error('vorname') border-red-500 @enderror">
                    @error('vorname') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nachname <span class="text-red-500">*</span></label>
                    <input type="text" name="nachname" value="{{ old('nachname') }}" required
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500 @error('nachname') border-red-500 @enderror">
                    @error('nachname') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">E-Mail</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500 @error('email') border-red-500 @enderror">
                @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Telefon</label>
                    <input type="text" name="telefon" value="{{ old('telefon') }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mobil</label>
                    <input type="text" name="mobil" value="{{ old('mobil') }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Straße</label>
                <input type="text" name="strasse" value="{{ old('strasse') }}"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">PLZ</label>
                    <input type="text" name="plz" value="{{ old('plz') }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ort</label>
                    <input type="text" name="ort" value="{{ old('ort') }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Geburtsdatum</label>
                    <input type="date" name="geburtsdatum" value="{{ old('geburtsdatum') }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                    <select name="status" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="aktiv" @selected(old('status', 'aktiv') === 'aktiv')>Aktiv</option>
                        <option value="inaktiv" @selected(old('status') === 'inaktiv')>Inaktiv</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Notizen</label>
                <textarea name="notizen" rows="3"
                          class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">{{ old('notizen') }}</textarea>
            </div>
            <div class="pt-2 flex gap-3">
                <button type="submit"
                        class="px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-md hover:bg-emerald-700 transition-colors">
                    Pächter anlegen
                </button>
                <a href="{{ route('paechter.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">Abbrechen</a>
            </div>
        </form>
    </div>
</div>
@endsection
