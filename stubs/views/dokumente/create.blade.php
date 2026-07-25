@extends('layouts.app')
@section('title', 'Dokument hochladen')
@section('page-title', 'Dokument hochladen')

@section('header-actions')
    <a href="{{ route('dokumente.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Übersicht</a>
@endsection

@section('content')

<div class="max-w-2xl">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form method="POST" action="{{ route('dokumente.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            {{-- Titel --}}
            <div>
                <label for="titel" class="block text-sm font-medium text-gray-700 mb-1">Titel <span class="text-red-500">*</span></label>
                <input type="text" id="titel" name="titel" value="{{ old('titel') }}" required
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500 @error('titel') border-red-300 @enderror"
                       placeholder="z.B. Rechnung Parzelle 12 – Juli 2025">
                @error('titel')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Kategorie --}}
            <div>
                <label for="kategorie" class="block text-sm font-medium text-gray-700 mb-1">Kategorie <span class="text-red-500">*</span></label>
                <select id="kategorie" name="kategorie" required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500 @error('kategorie') border-red-300 @enderror">
                    <option value="">Bitte wählen …</option>
                    <option value="angebot"   @selected(old('kategorie') === 'angebot')>Angebot</option>
                    <option value="rechnung"  @selected(old('kategorie') === 'rechnung')>Rechnung</option>
                    <option value="zahlung"   @selected(old('kategorie') === 'zahlung')>Zahlung</option>
                    <option value="sonstiges" @selected(old('kategorie') === 'sonstiges')>Sonstiges</option>
                </select>
                @error('kategorie')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Datei --}}
            <div>
                <label for="datei" class="block text-sm font-medium text-gray-700 mb-1">Datei <span class="text-red-500">*</span></label>
                <input type="file" id="datei" name="datei" required
                       accept=".pdf,.jpg,.jpeg,.png,.gif,.webp,.doc,.docx,.xls,.xlsx"
                       class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 @error('datei') border border-red-300 rounded-md @enderror">
                <p class="mt-1 text-xs text-gray-400">Erlaubt: PDF, Bilder (JPG, PNG, GIF, WEBP), Word (DOC, DOCX), Excel (XLS, XLSX) – max. 20 MB</p>
                @error('datei')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Dokument-Datum --}}
            <div>
                <label for="dokument_datum" class="block text-sm font-medium text-gray-700 mb-1">Dokumentdatum</label>
                <input type="date" id="dokument_datum" name="dokument_datum" value="{{ old('dokument_datum') }}"
                       class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                <p class="mt-1 text-xs text-gray-400">z.B. Rechnungsdatum, Angebotsdatum</p>
            </div>

            {{-- Pächter (optional) --}}
            <div>
                <label for="paechter_id" class="block text-sm font-medium text-gray-700 mb-1">Pächter <span class="text-gray-400 font-normal">(optional)</span></label>
                <select id="paechter_id" name="paechter_id"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">Kein Pächter</option>
                    @foreach($paechter as $p)
                        <option value="{{ $p->id }}" @selected(old('paechter_id') == $p->id)>
                            {{ $p->voller_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Vertrag (optional) --}}
            <div>
                <label for="vertrag_id" class="block text-sm font-medium text-gray-700 mb-1">Vertrag <span class="text-gray-400 font-normal">(optional)</span></label>
                <select id="vertrag_id" name="vertrag_id"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">Kein Vertrag</option>
                    @foreach($vertraege as $v)
                        <option value="{{ $v->id }}" @selected(old('vertrag_id') == $v->id)>
                            {{ $v->paechter?->voller_name ?? '–' }} – Stellplatz {{ $v->stellplatz?->nummer ?? '?' }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Beschreibung --}}
            <div>
                <label for="beschreibung" class="block text-sm font-medium text-gray-700 mb-1">Beschreibung <span class="text-gray-400 font-normal">(optional)</span></label>
                <textarea id="beschreibung" name="beschreibung" rows="3"
                          class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500"
                          placeholder="Zusätzliche Informationen zum Dokument …">{{ old('beschreibung') }}</textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="px-5 py-2 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-md shadow-sm transition-colors">
                    Hochladen
                </button>
                <a href="{{ route('dokumente.index') }}"
                   class="px-5 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-md shadow-sm transition-colors">
                    Abbrechen
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
