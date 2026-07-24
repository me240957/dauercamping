@extends('layouts.app')

@section('title', 'Stellplätze')
@section('page-title', 'Stellplätze')

@section('header-actions')
    <a href="{{ route('stellplaetze.create') }}"
       class="inline-flex items-center px-3 py-1.5 bg-emerald-600 text-white text-sm font-medium rounded-md hover:bg-emerald-700 transition-colors">
        <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Neuer Stellplatz
    </a>
@endsection

@section('content')

{{-- Suche & Filter --}}
<form method="GET" class="flex gap-3 mb-4">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nummer, Bezeichnung, Lage …"
           class="flex-1 border border-gray-300 rounded-md text-sm px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500">
    <select name="status" class="border border-gray-300 rounded-md text-sm px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500">
        <option value="">Alle Status</option>
        <option value="aktiv" @selected(request('status') === 'aktiv')>Aktiv</option>
        <option value="inaktiv" @selected(request('status') === 'inaktiv')>Inaktiv</option>
        <option value="gesperrt" @selected(request('status') === 'gesperrt')>Gesperrt</option>
    </select>
    <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-md hover:bg-gray-200 transition-colors">Suchen</button>
    @if(request()->hasAny(['search', 'status']))
        <a href="{{ route('stellplaetze.index') }}" class="px-4 py-2 text-gray-500 text-sm hover:text-gray-700">Zurücksetzen</a>
    @endif
</form>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nummer</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Bezeichnung / Lage</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Größe</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pächter</th>
                <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aktionen</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($stellplaetze as $sp)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3 text-sm font-semibold text-gray-900">{{ $sp->nummer }}</td>
                    <td class="px-5 py-3 text-sm text-gray-600">
                        {{ $sp->bezeichnung }}
                        @if($sp->lage)
                            <span class="text-gray-400">· {{ $sp->lage }}</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-sm text-gray-600">
                        {{ $sp->groesse_qm ? number_format($sp->groesse_qm, 0, ',', '.') . ' m²' : '–' }}
                    </td>
                    <td class="px-5 py-3">
                        @php $badge = ['aktiv'=>'green','inaktiv'=>'gray','gesperrt'=>'red'][$sp->status] ?? 'gray'; @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-{{ $badge }}-100 text-{{ $badge }}-800">
                            {{ ucfirst($sp->status) }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-sm text-gray-600">
                        @if($sp->aktiverVertrag)
                            {{ $sp->aktiverVertrag->paechter->voller_name }}
                        @else
                            <span class="text-gray-400">–</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('stellplaetze.show', $sp) }}"
                               class="text-xs text-emerald-600 hover:text-emerald-800 font-medium">Details</a>
                            <a href="{{ route('stellplaetze.edit', $sp) }}"
                               class="text-xs text-gray-500 hover:text-gray-700 font-medium">Bearbeiten</a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-5 py-10 text-center text-sm text-gray-500">
                        Keine Stellplätze gefunden.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    @if($stellplaetze->hasPages())
        <div class="px-5 py-3 border-t border-gray-200">
            {{ $stellplaetze->links() }}
        </div>
    @endif
</div>

@endsection
