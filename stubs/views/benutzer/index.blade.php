@extends('layouts.app')
@section('title', 'Benutzerverwaltung')
@section('page-title', 'Benutzerverwaltung')

@section('header-actions')
    <a href="{{ route('benutzer.create') }}"
       class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-md shadow-sm transition-colors">
        <svg class="mr-2 -ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Benutzer anlegen
    </a>
@endsection

@section('content')

<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">E-Mail</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Rolle</th>
                <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Erstellt</th>
                <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aktionen</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($benutzer as $b)
                <tr class="hover:bg-gray-50 transition-colors {{ !$b->aktiv ? 'opacity-60' : '' }}">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            <div class="h-8 w-8 rounded-full flex items-center justify-center text-white text-sm font-semibold flex-shrink-0
                                {{ $b->isAdmin() ? 'bg-red-500' : ($b->isVerwalter() ? 'bg-blue-500' : 'bg-gray-400') }}">
                                {{ strtoupper(substr($b->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $b->name }}
                                    @if($b->id === auth()->id())
                                        <span class="ml-1 text-xs text-gray-400">(du)</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3 text-sm text-gray-600">{{ $b->email }}</td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $b->role_badge }}">
                            {{ $b->role_label }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-center">
                        @if($b->aktiv)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                Aktiv
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-700">
                                Gesperrt
                            </span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-sm text-gray-400">
                        {{ $b->created_at->format('d.m.Y') }}
                    </td>
                    <td class="px-5 py-3 text-right">
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('benutzer.edit', $b) }}"
                               class="text-xs font-medium text-blue-600 hover:text-blue-800 transition-colors">
                                Bearbeiten
                            </a>
                            @if($b->id !== auth()->id())
                                <form method="POST" action="{{ route('benutzer.destroy', $b) }}"
                                      onsubmit="return confirm('Benutzer „{{ addslashes($b->name) }}" wirklich löschen?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="text-xs font-medium text-red-500 hover:text-red-700 transition-colors">
                                        Löschen
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- Legende --}}
<div class="mt-4 flex items-center gap-6 text-xs text-gray-400">
    <span class="flex items-center gap-1.5">
        <span class="inline-block h-3 w-3 rounded-full bg-red-500"></span> Administrator
    </span>
    <span class="flex items-center gap-1.5">
        <span class="inline-block h-3 w-3 rounded-full bg-blue-500"></span> Verwalter
    </span>
    <span class="flex items-center gap-1.5">
        <span class="inline-block h-3 w-3 rounded-full bg-gray-400"></span> Leser
    </span>
</div>

@endsection
