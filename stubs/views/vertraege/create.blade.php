@extends('layouts.app')
@section('title', 'Vertrag anlegen')
@section('page-title', 'Vertrag anlegen')
@section('header-actions')
    <a href="{{ route('vertraege.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Zurück</a>
@endsection
@section('content')
<div class="max-w-xl">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form method="POST" action="{{ route('vertraege.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Stellplatz <span class="text-red-500">*</span></label>
                <select name="stellplatz_id" required class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500 @error('stellplatz_id') border-red-500 @enderror">
                    <option value="">– bitte wählen –</option>
                    @foreach($stellplaetze as $sp)
                        <option value="{{ $sp->id }}" @selected(old('stellplatz_id', $preselect_stellplatz) == $sp->id)>
                            {{ $sp->nummer }}{{ $sp->bezeichnung ? ' – ' . $sp->bezeichnung : '' }}
                        </option>
                    @endforeach
                </select>
                @error('stellplatz_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pächter <span class="text-red-500">*</span></label>
                <select name="paechter_id" required class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500 @error('paechter_id') border-red-500 @enderror">
                    <option value="">– bitte wählen –</option>
                    @foreach($paechter as $p)
                        <option value="{{ $p->id }}" @selected(old('paechter_id') == $p->id)>{{ $p->voller_name }}</option>
                    @endforeach
                </select>
                @error('paechter_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Beginn <span class="text-red-500">*</span></label>
                    <input type="date" name="beginn" value="{{ old('beginn') }}" required
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                    @error('beginn') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ende <span class="text-gray-400 font-normal">(leer = unbefristet)</span></label>
                    <input type="date" name="ende" value="{{ old('ende') }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jahresbetrag (€) <span class="text-red-500">*</span></label>
                    <input type="number" name="jahresbetrag" value="{{ old('jahresbetrag') }}" step="0.01" min="0" required
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                    @error('jahresbetrag') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Zahlungsrhythmus <span class="text-red-500">*</span></label>
                    <select name="zahlungsrhythmus" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="jaehrlich" @selected(old('zahlungsrhythmus','jaehrlich')==='jaehrlich')>Jährlich</option>
                        <option value="halbjaehrlich" @selected(old('zahlungsrhythmus')==='halbjaehrlich')>Halbjährlich</option>
                        <option value="vierteljaehrlich" @selected(old('zahlungsrhythmus')==='vierteljaehrlich')>Vierteljährlich</option>
                        <option value="monatlich" @selected(old('zahlungsrhythmus')==='monatlich')>Monatlich</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                <select name="status" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="aktiv" @selected(old('status','aktiv')==='aktiv')>Aktiv</option>
                    <option value="gekuendigt" @selected(old('status')==='gekuendigt')>Gekündigt</option>
                    <option value="beendet" @selected(old('status')==='beendet')>Beendet</option>
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
                    Vertrag anlegen
                </button>
                <a href="{{ route('vertraege.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">Abbrechen</a>
            </div>
        </form>
    </div>
</div>
@endsection
