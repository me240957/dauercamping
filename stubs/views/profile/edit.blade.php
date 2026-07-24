@extends('layouts.app')

@section('title', 'Mein Profil')
@section('page-title', 'Mein Profil')

@section('content')
<div class="max-w-xl space-y-6">

    {{-- Profildaten --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-sm font-semibold text-gray-900 mb-4">Profilinformationen</h2>

        <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
            @csrf
            @method('patch')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">E-Mail</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-4 pt-1">
                <button type="submit"
                        class="px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-md hover:bg-emerald-700 transition-colors">
                    Speichern
                </button>
                @if(session('status') === 'profile-updated')
                    <span class="text-sm text-green-600">Gespeichert.</span>
                @endif
            </div>
        </form>
    </div>

    {{-- Passwort ändern --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-sm font-semibold text-gray-900 mb-4">Passwort ändern</h2>

        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf
            @method('put')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Aktuelles Passwort</label>
                <input type="password" name="current_password" autocomplete="current-password"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                @error('current_password', 'updatePassword')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Neues Passwort</label>
                <input type="password" name="password" autocomplete="new-password"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                @error('password', 'updatePassword')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Passwort bestätigen</label>
                <input type="password" name="password_confirmation" autocomplete="new-password"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
            </div>

            <div class="flex items-center gap-4 pt-1">
                <button type="submit"
                        class="px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-md hover:bg-emerald-700 transition-colors">
                    Passwort ändern
                </button>
                @if(session('status') === 'password-updated')
                    <span class="text-sm text-green-600">Passwort geändert.</span>
                @endif
            </div>
        </form>
    </div>

    {{-- Konto löschen --}}
    <div class="bg-white rounded-lg border border-red-200 p-6">
        <h2 class="text-sm font-semibold text-red-700 mb-2">Konto löschen</h2>
        <p class="text-xs text-gray-500 mb-4">Alle Daten werden unwiderruflich gelöscht.</p>

        <form method="POST" action="{{ route('profile.destroy') }}"
              onsubmit="return confirm('Konto wirklich löschen? Diese Aktion kann nicht rückgängig gemacht werden.')">
            @csrf
            @method('delete')

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Passwort zur Bestätigung</label>
                <input type="password" name="password"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                @error('password', 'userDeletion')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 transition-colors">
                Konto löschen
            </button>
        </form>
    </div>

</div>
@endsection
