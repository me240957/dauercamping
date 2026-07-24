@extends('layouts.app')

@section('title', 'Pächter')
@section('page-title', 'Pächter')

@section('header-actions')
    <a href="{{ route('paechter.create') }}"
       class="inline-flex items-center px-3 py-1.5 bg-emerald-600 text-white text-sm font-medium rounded-md hover:bg-emerald-700 transition-colors">
        <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Neuer Pächter
    </a>
@endsection

@section('content')
<form method="GET" class="flex gap-3 mb-4">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, E-Mail, Ort …"
           class="flex-1 border border-gray-300 rounded-md text-sm px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500">
    <select name="status" class="border border-gray-300 rounded-md text-sm px-3 py-2">
        <option value="">Alle Status</option>
        <option value="aktiv" @selected(request('status') === 'aktiv')>Aktiv</option>
        <option value="inaktiv" @selected(request('status') === 'inaktiv')>Inaktiv</option>
    </select>
    <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-md hover:bg-gray-200">Suchen</button>
    @if(request()->hasAny(['search','status']))
        <a href="{{ route('paechter.index') }}" class="px-4 py-2 text-gray-500 text-sm hover:text-gray-700">Zurücksetzen</a>
    @endif
</form>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kontakt</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ort</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Verträge</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aktionen</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($paechter as $p)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3 text-sm font-medium text-gray-900">{{ $p->voller_name }}</td>
                    <td class="px-5 py-3 text-sm text-gray-600">
                        @if($p->email) <div>{{ $p->email }}</div> @endif
                        @if($p->telefon) <div class="text-xs text-gray-400">{{ $p->telefon }}</div> @endif
                    </td>
                    <td class="px-5 py-3 text-sm text-gray-600">{{ $p->ort ?: '–' }}</td>
                    <td class="px-5 py-3 text-sm text-gray-600">{{ $p->vertraege_count }}</td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $p->status === 'aktiv' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                            {{ ucfirst($p->status) }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('paechter.show', $p) }}" class="text-xs text-emerald-600 hover:text-emerald-800 font-medium">Details</a>
                            <a href="{{ route('paechter.edit', $p) }}" class="text-xs text-gray-500 hover:text-gray-700 font-medium">Bearbeiten</a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-5 py-10 text-center text-sm text-gray-500">Keine Pächter gefunden.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    @if($paechter->hasPages())
        <div class="px-5 py-3 border-t border-gray-200">{{ $paechter->links() }}</div>
    @endif
</div>
@endsection
