@extends('layouts.app')
@section('title', 'Zahlungen')
@section('page-title', 'Zahlungen')

@section('header-actions')
    <a href="{{ route('zahlungen.create') }}"
       class="inline-flex items-center px-3 py-1.5 bg-emerald-600 text-white text-sm font-medium rounded-md hover:bg-emerald-700 transition-colors">
        <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Neue Zahlung
    </a>
@endsection

@section('content')

<form method="GET" class="flex flex-wrap gap-3 mb-4">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Pächter suchen …"
           class="flex-1 min-w-48 border border-gray-300 rounded-md text-sm px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500">
    <select name="status" class="border border-gray-300 rounded-md text-sm px-3 py-2">
        <option value="">Alle Status</option>
        <option value="offen"     @selected(request('status')==='offen')>Offen</option>
        <option value="bezahlt"   @selected(request('status')==='bezahlt')>Bezahlt</option>
        <option value="gemahnt"   @selected(request('status')==='gemahnt')>Gemahnt</option>
        <option value="storniert" @selected(request('status')==='storniert')>Storniert</option>
    </select>
    <select name="jahr" class="border border-gray-300 rounded-md text-sm px-3 py-2">
        <option value="">Alle Jahre</option>
        @foreach($jahre as $j)
            <option value="{{ $j }}" @selected(request('jahr') == $j)>{{ $j }}</option>
        @endforeach
    </select>
    <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-md hover:bg-gray-200">Filtern</button>
    @if(request()->hasAny(['search','status','jahr']))
        <a href="{{ route('zahlungen.index') }}" class="px-4 py-2 text-gray-500 text-sm hover:text-gray-700">Zurücksetzen</a>
    @endif
</form>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pächter</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Stellplatz</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Jahr</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Betrag</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Fällig</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aktionen</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($zahlungen as $z)
                <tr class="hover:bg-gray-50 transition-colors {{ $z->ist_ueberfaellig ? 'bg-red-50' : '' }}">
                    <td class="px-5 py-3 text-sm font-medium text-gray-900">
                        {{ $z->vertrag->paechter->voller_name }}
                    </td>
                    <td class="px-5 py-3 text-sm text-gray-600">{{ $z->vertrag->stellplatz->nummer }}</td>
                    <td class="px-5 py-3 text-sm text-gray-600">{{ $z->jahr }}</td>
                    <td class="px-5 py-3 text-sm font-semibold text-gray-900">
                        {{ number_format($z->betrag, 2, ',', '.') }} €
                    </td>
                    <td class="px-5 py-3 text-sm {{ $z->ist_ueberfaellig ? 'text-red-600 font-medium' : 'text-gray-600' }}">
                        {{ $z->faellig_am ? $z->faellig_am->format('d.m.Y') : '–' }}
                        @if($z->ist_ueberfaellig) <span class="text-xs">(überfällig)</span> @endif
                    </td>
                    <td class="px-5 py-3">
                        @php $badge = ['bezahlt'=>'green','offen'=>'yellow','gemahnt'=>'red','storniert'=>'gray'][$z->status] ?? 'gray'; @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-{{ $badge }}-100 text-{{ $badge }}-800">
                            {{ $z->status_label }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            @if($z->status === 'offen')
                                <form method="POST" action="{{ route('zahlungen.bezahlt', $z) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="text-xs text-emerald-600 hover:text-emerald-800 font-medium">Bezahlt</button>
                                </form>
                            @endif
                            <a href="{{ route('zahlungen.edit', $z) }}" class="text-xs text-gray-500 hover:text-gray-700 font-medium">Bearbeiten</a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-5 py-10 text-center text-sm text-gray-500">Keine Zahlungen gefunden.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    @if($zahlungen->hasPages())
        <div class="px-5 py-3 border-t border-gray-200">{{ $zahlungen->links() }}</div>
    @endif
</div>

@endsection
