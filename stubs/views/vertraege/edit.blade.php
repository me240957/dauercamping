@extends('layouts.app')
@section('title', 'Vertrag bearbeiten')
@section('page-title', 'Vertrag bearbeiten')
@section('header-actions')
    <a href="{{ route('vertraege.show', $vertrag) }}" class="text-sm text-gray-500 hover:text-gray-700">← Zurück</a>
@endsection
@section('content')
<div class="max-w-xl">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form method="POST" action="{{ route('vertraege.update', $vertrag) }}" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Stellplatz *</label>
                <select name="stellplatz_id" required class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                    @foreach($stellplaetze as $sp)
                        <option value="{{ $sp->id }}" @selected(old('stellplatz_id', $vertrag->stellplatz_id) == $sp->id)>{{ $sp->nummer }}{{ $sp->bezeichnung ? ' – ' . $sp->bezeichnung : '' }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pächter *</label>
                <select name="paechter_id" required class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                    @foreach($paechter as $p)
                        <option value="{{ $p->id }}" @selected(old('paechter_id', $vertrag->paechter_id) == $p->id)>{{ $p->voller_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Beginn *</label>
                    <input type="date" name="beginn" value="{{ old('beginn', $vertrag->beginn->format('Y-m-d')) }}" required
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ende <span class="text-gray-400 font-normal">(leer = unbefristet)</span></label>
                    <input type="date" name="ende" value="{{ old('ende', $vertrag->ende?->format('Y-m-d')) }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jahresbetrag (€) *</label>
                    <input type="number" name="jahresbetrag" value="{{ old('jahresbetrag', $vertrag->jahresbetrag) }}" step="0.01" min="0" required
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Zahlungsrhythmus *</label>
                    <select name="zahlungsrhythmus" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="jaehrlich" @selected(old('zahlungsrhythmus',$vertrag->zahlungsrhythmus)==='jaehrlich')>Jährlich</option>
                        <option value="halbjaehrlich" @selected(old('zahlungsrhythmus',$vertrag->zahlungsrhythmus)==='halbjaehrlich')>Halbjährlich</option>
                        <option value="vierteljaehrlich" @selected(old('zahlungsrhythmus',$vertrag->zahlungsrhythmus)==='vierteljaehrlich')>Vierteljährlich</option>
                        <option value="monatlich" @selected(old('zahlungsrhythmus',$vertrag->zahlungsrhythmus)==='monatlich')>Monatlich</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                    <select name="status" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="aktiv" @selected(old('status',$vertrag->status)==='aktiv')>Aktiv</option>
                        <option value="gekuendigt" @selected(old('status',$vertrag->status)==='gekuendigt')>Gekündigt</option>
                        <option value="beendet" @selected(old('status',$vertrag->status)==='beendet')>Beendet</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kündigungsdatum</label>
                    <input type="date" name="kuendigungsdatum" value="{{ old('kuendigungsdatum', $vertrag->kuendigungsdatum?->format('Y-m-d')) }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Notizen</label>
                <textarea name="notizen" rows="3"
                          class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">{{ old('notizen', $vertrag->notizen) }}</textarea>
            </div>
            <div class="pt-2 flex gap-3">
                <button type="submit" class="px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-md hover:bg-emerald-700 transition-colors">
                    Änderungen speichern
                </button>
                <a href="{{ route('vertraege.show', $vertrag) }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">Abbrechen</a>
            </div>
        </form>
    </div>
    <div class="mt-4 bg-white rounded-lg border border-red-200 p-4">
        <h3 class="text-sm font-semibold text-red-700 mb-2">Vertrag löschen</h3>
        <p class="text-xs text-gray-500 mb-3">Nur möglich, wenn keine offenen Zahlungen bestehen.</p>
        <form method="POST" action="{{ route('vertraege.destroy', $vertrag) }}"
              onsubmit="return confirm('Diesen Vertrag wirklich löschen?')">
            @csrf @method('DELETE')
            <button type="submit" class="px-3 py-1.5 bg-red-600 text-white text-xs font-medium rounded-md hover:bg-red-700 transition-colors">Löschen</button>
        </form>
    </div>
</div>
@endsection
