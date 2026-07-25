@extends('layouts.app')
@section('title', 'Benutzer bearbeiten')
@section('page-title', 'Benutzer bearbeiten')

@section('header-actions')
    <a href="{{ route('benutzer.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Übersicht</a>
@endsection

@section('content')

@php $istEigenesKonto = $benutzer->id === auth()->id(); @endphp

<div class="max-w-lg space-y-4">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">

        @if($istEigenesKonto)
            <div class="mb-5 rounded-md bg-amber-50 border border-amber-200 p-3 text-sm text-amber-800">
                Du bearbeitest dein eigenes Konto. Rolle und Status können nicht geändert werden.
            </div>
        @endif

        <form method="POST" action="{{ route('benutzer.update', $benutzer) }}" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Name --}}
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name', $benutzer->name) }}" required
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500 @error('name') border-red-300 @enderror">
                @error('name')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- E-Mail --}}
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">E-Mail <span class="text-red-500">*</span></label>
                <input type="email" id="email" name="email" value="{{ old('email', $benutzer->email) }}" required
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500 @error('email') border-red-300 @enderror">
                @error('email')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Rolle (nur wenn nicht eigenes Konto) --}}
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Rolle</label>
                @if($istEigenesKonto)
                    <input type="hidden" name="role" value="{{ $benutzer->role }}">
                    <div class="w-full border border-gray-200 rounded-md px-3 py-2 text-sm bg-gray-50 text-gray-500">
                        {{ $benutzer->role_label }}
                    </div>
                @else
                    <select id="role" name="role" required
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="leser"     @selected(old('role', $benutzer->role) === 'leser')>Leser</option>
                        <option value="verwalter" @selected(old('role', $benutzer->role) === 'verwalter')>Verwalter</option>
                        <option value="admin"     @selected(old('role', $benutzer->role) === 'admin')>Administrator</option>
                    </select>
                @endif
            </div>

            {{-- Neues Passwort (optional) --}}
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                    Neues Passwort <span class="text-gray-400 font-normal">(leer lassen = unverändert)</span>
                </label>
                <input type="password" id="password" name="password"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500 @error('password') border-red-300 @enderror"
                       placeholder="Mindestens 8 Zeichen">
                @error('password')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Passwort bestätigen --}}
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Passwort bestätigen</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500"
                       placeholder="Nur ausfüllen wenn neues Passwort gesetzt wird">
            </div>

            {{-- Aktiv (nur wenn nicht eigenes Konto) --}}
            @if(!$istEigenesKonto)
                <div class="flex items-center gap-3">
                    <input type="hidden" name="aktiv" value="0">
                    <input type="checkbox" id="aktiv" name="aktiv" value="1"
                           {{ old('aktiv', $benutzer->aktiv) ? 'checked' : '' }}
                           class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                    <label for="aktiv" class="text-sm font-medium text-gray-700">Benutzer ist aktiv</label>
                </div>
            @endif

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="px-5 py-2 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-md shadow-sm transition-colors">
                    Speichern
                </button>
                <a href="{{ route('benutzer.index') }}"
                   class="px-5 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-md shadow-sm transition-colors">
                    Abbrechen
                </a>
            </div>
        </form>
    </div>

    {{-- Gefahrenzone --}}
    @if(!$istEigenesKonto)
        <div class="bg-white rounded-lg shadow-sm border border-red-200 p-4">
            <h3 class="text-sm font-medium text-red-700 mb-2">Benutzer löschen</h3>
            <p class="text-xs text-gray-500 mb-3">Der Benutzer wird dauerhaft gelöscht und kann sich nicht mehr anmelden.</p>
            <form method="POST" action="{{ route('benutzer.destroy', $benutzer) }}"
                  onsubmit="return confirm('Benutzer „{{ addslashes($benutzer->name) }}" wirklich löschen?')">
                @csrf @method('DELETE')
                <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-md transition-colors">
                    Benutzer löschen
                </button>
            </form>
        </div>
    @endif
</div>

@endsection
