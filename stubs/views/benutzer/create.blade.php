@extends('layouts.app')
@section('title', 'Benutzer anlegen')
@section('page-title', 'Benutzer anlegen')

@section('header-actions')
    <a href="{{ route('benutzer.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Übersicht</a>
@endsection

@section('content')

<div class="max-w-lg">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form method="POST" action="{{ route('benutzer.store') }}" class="space-y-5">
            @csrf

            {{-- Name --}}
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500 @error('name') border-red-300 @enderror"
                       placeholder="Vor- und Nachname">
                @error('name')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- E-Mail --}}
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">E-Mail <span class="text-red-500">*</span></label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500 @error('email') border-red-300 @enderror"
                       placeholder="benutzer@example.de">
                @error('email')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Rolle --}}
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Rolle <span class="text-red-500">*</span></label>
                <select id="role" name="role" required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="leser"     @selected(old('role', 'leser') === 'leser')>Leser – kann nur lesen</option>
                    <option value="verwalter" @selected(old('role') === 'verwalter')>Verwalter – kann Daten bearbeiten</option>
                    <option value="admin"     @selected(old('role') === 'admin')>Administrator – voller Zugriff</option>
                </select>
                @error('role')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Passwort --}}
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Passwort <span class="text-red-500">*</span></label>
                <input type="password" id="password" name="password" required
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500 @error('password') border-red-300 @enderror"
                       placeholder="Mindestens 8 Zeichen">
                @error('password')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Passwort bestätigen --}}
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Passwort bestätigen <span class="text-red-500">*</span></label>
                <input type="password" id="password_confirmation" name="password_confirmation" required
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500"
                       placeholder="Passwort wiederholen">
            </div>

            {{-- Aktiv --}}
            <div class="flex items-center gap-3">
                <input type="hidden" name="aktiv" value="0">
                <input type="checkbox" id="aktiv" name="aktiv" value="1" checked
                       class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                <label for="aktiv" class="text-sm font-medium text-gray-700">Benutzer ist aktiv</label>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="px-5 py-2 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-md shadow-sm transition-colors">
                    Benutzer anlegen
                </button>
                <a href="{{ route('benutzer.index') }}"
                   class="px-5 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-md shadow-sm transition-colors">
                    Abbrechen
                </a>
            </div>
        </form>
    </div>

    {{-- Rollen-Erklärung --}}
    <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <h3 class="text-sm font-semibold text-blue-800 mb-2">Rollen-Übersicht</h3>
        <dl class="space-y-1.5 text-sm">
            <div class="flex gap-2">
                <dt class="font-medium text-red-700 w-24 flex-shrink-0">Administrator</dt>
                <dd class="text-blue-700">Vollzugriff inkl. Benutzerverwaltung</dd>
            </div>
            <div class="flex gap-2">
                <dt class="font-medium text-blue-700 w-24 flex-shrink-0">Verwalter</dt>
                <dd class="text-blue-700">Daten anlegen, bearbeiten und löschen</dd>
            </div>
            <div class="flex gap-2">
                <dt class="font-medium text-gray-600 w-24 flex-shrink-0">Leser</dt>
                <dd class="text-blue-700">Nur Lesezugriff, keine Änderungen</dd>
            </div>
        </dl>
    </div>
</div>

@endsection
