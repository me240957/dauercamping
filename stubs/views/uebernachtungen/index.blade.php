@extends('layouts.app')
@section('title', 'Übernachtungen')
@section('page-title', 'Übernachtungen')

@section('header-actions')
    <a href="{{ route('uebernachtungen.statistik') }}"
       class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-50 transition-colors">
        <svg class="h-4 w-4 mr-1.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
        </svg>
        Statistik
    </a>
    <a href="{{ route('uebernachtungen.pdf', request()->only(['jahr','search'])) }}"
       class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-50 transition-colors">
        <svg class="h-4 w-4 mr-1.5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
        </svg>
        PDF
    </a>
    <a href="{{ route('uebernachtungen.create') }}"
       class="inline-flex items-center px-3 py-1.5 bg-emerald-600 text-white text-sm font-medium rounded-md hover:bg-emerald-700 transition-colors">
        <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Erfassen
    </a>
@endsection

@section('content')

<form method="GET" class="flex gap-3 mb-4">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Pächter, Stellplatz …"
           class="flex-1 border border-gray-300 rounded-md text-sm px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500">
    <select name="jahr" class="border border-gray-300 rounded-md text-sm px-3 py-2">
        <option value="">Alle Jahre</option>
        @foreach($jahre as $j)
            <option value="{{ $j }}" @selected(request('jahr') == $j)>{{ $j }}</option>
        @endforeach
    </select>
    <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-md hover:bg-gray-200">Filtern</button>
    @if(request()->hasAny(['search','jahr']))
        <a href="{{ route('uebernachtungen.index') }}" class="px-4 py-2 text-gray-500 text-sm hover:text-gray-700">Zurücksetzen</a>
    @endif
</form>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Anreise</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Abreise</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pächter</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Stellplatz</th>
                <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Nächte</th>
                <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Personen</th>
                <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Personennächte</th>
                <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aktionen</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($uebernachtungen as $u)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3 text-sm font-medium text-gray-900">
                        {{ $u->datum->format('d.m.Y') }}
                    </td>
                    <td class="px-5 py-3 text-sm text-gray-600">
                        {{ $u->abreisedatum?->format('d.m.Y') ?? '–' }}
                    </td>
                    <td class="px-5 py-3 text-sm text-gray-900">
                        {{ $u->vertrag->paechter->voller_name }}
                    </td>
                    <td class="px-5 py-3 text-sm text-gray-600">
                        {{ $u->vertrag->stellplatz->nummer }}
                    </td>
                    <td class="px-5 py-3 text-sm text-gray-900 text-center font-semibold">
                        {{ $u->anzahl_naechte }}
                    </td>
                    <td class="px-5 py-3 text-sm text-gray-900 text-center">
                        {{ $u->anzahl_personen }}
                    </td>
                    <td class="px-5 py-3 text-sm text-gray-900 text-center">
                        {{ $u->personennaechte }}
                    </td>
                    <td class="px-5 py-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('uebernachtungen.edit', $u) }}"
                               class="text-xs text-gray-500 hover:text-gray-700 font-medium">Bearbeiten</a>
                            <form method="POST" action="{{ route('uebernachtungen.destroy', $u) }}"
                                  onsubmit="return confirm('Eintrag löschen?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-medium">Löschen</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-5 py-10 text-center text-sm text-gray-500">
                        Noch keine Übernachtungen erfasst.
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if($uebernachtungen->count() > 0)
        <tfoot class="bg-gray-50 border-t border-gray-200">
            <tr>
                <td colspan="4" class="px-5 py-2 text-xs font-semibold text-gray-500 uppercase">Summe (diese Seite)</td>
                <td class="px-5 py-2 text-sm font-bold text-gray-900 text-center">
                    {{ $uebernachtungen->sum('anzahl_naechte') }}
                </td>
                <td class="px-5 py-2 text-sm font-bold text-gray-900 text-center">
                    {{ $uebernachtungen->sum('anzahl_personen') }}
                </td>
                <td class="px-5 py-2 text-sm font-bold text-gray-900 text-center">
                    {{ $uebernachtungen->sum('personennaechte') }}
                </td>
                <td></td>
            </tr>
        </tfoot>
        @endif
    </table>
    @if($uebernachtungen->hasPages())
        <div class="px-5 py-3 border-t border-gray-200">{{ $uebernachtungen->links() }}</div>
    @endif
</div>

@endsection
