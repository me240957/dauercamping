@extends('layouts.app')
@section('title', 'Zahlung bearbeiten')
@section('page-title', 'Zahlung bearbeiten')
@section('header-actions')
    <a href="{{ route('zahlungen.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Zurück</a>
@endsection
@section('content')
<div class="max-w-xl">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form method="POST" action="{{ route('zahlungen.update', $zahlung) }}" class="space-y-4">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Vertrag <span class="text-red-500">*</span></label>
                <select name="vertrag_id" required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                    @foreach($vertraege as $v)
                        <option value="{{ $v->id }}" @selected(old('vertrag_id', $zahlung->vertrag_id) == $v->id)>
                            {{ $v->paechter->voller_name }} – Stellplatz {{ $v->stellplatz->nummer }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jahr <span class="text-red-500">*</span></label>
                    <input type="number" name="jahr" value="{{ old('jahr', $zahlung->jahr) }}" min="2000" max="2100" required
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Betrag (€) <span class="text-red-500">*</span></label>
                    <input type="number" name="betrag" value="{{ old('betrag', $zahlung->betrag) }}" step="0.01" min="0" required
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fällig am</label>
                    <input type="date" name="faellig_am" value="{{ old('faellig_am', $zahlung->faellig_am?->format('Y-m-d')) }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bezahlt am</label>
                    <input type="date" name="bezahlt_am" value="{{ old('bezahlt_am', $zahlung->bezahlt_am?->format('Y-m-d')) }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                    <select name="status" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="offen"     @selected(old('status',$zahlung->status)==='offen')>Offen</option>
                        <option value="bezahlt"   @selected(old('status',$zahlung->status)==='bezahlt')>Bezahlt</option>
                        <option value="gemahnt"   @selected(old('status',$zahlung->status)==='gemahnt')>Gemahnt</option>
                        <option value="storniert" @selected(old('status',$zahlung->status)==='storniert')>Storniert</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Zahlungsart</label>
                    <input type="text" name="zahlungsart" value="{{ old('zahlungsart', $zahlung->zahlungsart) }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Referenz / Verwendungszweck</label>
                <input type="text" name="referenz" value="{{ old('referenz', $zahlung->referenz) }}"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Notizen</label>
                <textarea name="notizen" rows="3"
                          class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">{{ old('notizen', $zahlung->notizen) }}</textarea>
            </div>

            <div class="pt-2 flex gap-3">
                <button type="submit"
                        class="px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-md hover:bg-emerald-700 transition-colors">
                    Änderungen speichern
                </button>
                <a href="{{ route('zahlungen.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">Abbrechen</a>
            </div>
        </form>
    </div>

    <div class="mt-4 bg-white rounded-lg border border-red-200 p-4">
        <h3 class="text-sm font-semibold text-red-700 mb-2">Zahlung löschen</h3>
        <form method="POST" action="{{ route('zahlungen.destroy', $zahlung) }}"
              onsubmit="return confirm('Diese Zahlung wirklich löschen?')">
            @csrf @method('DELETE')
            <button type="submit" class="px-3 py-1.5 bg-red-600 text-white text-xs font-medium rounded-md hover:bg-red-700 transition-colors">
                Löschen
            </button>
        </form>
    </div>
</div>
@endsection
