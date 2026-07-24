@extends('layouts.app')
@section('title', 'Zahlung anlegen')
@section('page-title', 'Zahlung anlegen')
@section('header-actions')
    <a href="{{ route('zahlungen.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Zurück</a>
@endsection
@section('content')
<div class="max-w-xl">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form method="POST" action="{{ route('zahlungen.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Vertrag <span class="text-red-500">*</span></label>
                <select name="vertrag_id" required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500 @error('vertrag_id') border-red-500 @enderror">
                    <option value="">– bitte wählen –</option>
                    @foreach($vertraege as $v)
                        <option value="{{ $v->id }}" @selected(old('vertrag_id', $preselect_vertrag) == $v->id)>
                            {{ $v->paechter->voller_name }} – Stellplatz {{ $v->stellplatz->nummer }}
                        </option>
                    @endforeach
                </select>
                @error('vertrag_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jahr <span class="text-red-500">*</span></label>
                    <input type="number" name="jahr" value="{{ old('jahr', now()->year) }}" min="2000" max="2100" required
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                    @error('jahr') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Betrag (€) <span class="text-red-500">*</span></label>
                    <input type="number" name="betrag" value="{{ old('betrag') }}" step="0.01" min="0" required
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                    @error('betrag') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fällig am</label>
                    <input type="date" name="faellig_am" value="{{ old('faellig_am') }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bezahlt am</label>
                    <input type="date" name="bezahlt_am" value="{{ old('bezahlt_am') }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                    <select name="status" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="offen"     @selected(old('status','offen')==='offen')>Offen</option>
                        <option value="bezahlt"   @selected(old('status')==='bezahlt')>Bezahlt</option>
                        <option value="gemahnt"   @selected(old('status')==='gemahnt')>Gemahnt</option>
                        <option value="storniert" @selected(old('status')==='storniert')>Storniert</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Zahlungsart</label>
                    <input type="text" name="zahlungsart" value="{{ old('zahlungsart') }}"
                           placeholder="Überweisung, Bar, SEPA …"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Referenz / Verwendungszweck</label>
                <input type="text" name="referenz" value="{{ old('referenz') }}"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Notizen</label>
                <textarea name="notizen" rows="3"
                          class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">{{ old('notizen') }}</textarea>
            </div>

            <div class="pt-2 flex gap-3">
                <button type="submit"
                        class="px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-md hover:bg-emerald-700 transition-colors">
                    Zahlung anlegen
                </button>
                <a href="{{ route('zahlungen.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">Abbrechen</a>
            </div>
        </form>
    </div>
</div>
@endsection
