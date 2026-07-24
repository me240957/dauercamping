@extends('layouts.app')

@section('title', 'Stellplatz ' . $stellplatz->nummer)
@section('page-title', 'Stellplatz ' . $stellplatz->nummer)

@section('header-actions')
    <a href="{{ route('stellplaetze.index') }}" class="text-sm text-gray-500 hover:text-gray-700 mr-2">← Zurück</a>
    <a href="{{ route('stellplaetze.edit', $stellplatz) }}"
       class="inline-flex items-center px-3 py-1.5 bg-emerald-600 text-white text-sm font-medium rounded-md hover:bg-emerald-700 transition-colors">
        Bearbeiten
    </a>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Stammdaten --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 lg:col-span-1">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Stammdaten</h3>
        <dl class="space-y-3">
            <div>
                <dt class="text-xs text-gray-500">Nummer</dt>
                <dd class="text-sm font-medium text-gray-900">{{ $stellplatz->nummer }}</dd>
            </div>
            @if($stellplatz->bezeichnung)
            <div>
                <dt class="text-xs text-gray-500">Bezeichnung</dt>
                <dd class="text-sm text-gray-900">{{ $stellplatz->bezeichnung }}</dd>
            </div>
            @endif
            <div>
                <dt class="text-xs text-gray-500">Größe</dt>
                <dd class="text-sm text-gray-900">{{ $stellplatz->groesse_qm ? number_format($stellplatz->groesse_qm, 0, ',', '.') . ' m²' : '–' }}</dd>
            </div>
            <div>
                <dt class="text-xs text-gray-500">Lage</dt>
                <dd class="text-sm text-gray-900">{{ $stellplatz->lage ?: '–' }}</dd>
            </div>
            <div>
                <dt class="text-xs text-gray-500">Status</dt>
                <dd>
                    @php $badge = ['aktiv'=>'green','inaktiv'=>'gray','gesperrt'=>'red'][$stellplatz->status] ?? 'gray'; @endphp
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-{{ $badge }}-100 text-{{ $badge }}-800">
                        {{ ucfirst($stellplatz->status) }}
                    </span>
                </dd>
            </div>
            @if($stellplatz->notizen)
            <div>
                <dt class="text-xs text-gray-500">Notizen</dt>
                <dd class="text-sm text-gray-700 whitespace-pre-line">{{ $stellplatz->notizen }}</dd>
            </div>
            @endif
        </dl>

        @if(!$stellplatz->ist_verpachtet)
            <div class="mt-5 pt-4 border-t border-gray-100">
                <a href="{{ route('vertraege.create', ['stellplatz_id' => $stellplatz->id]) }}"
                   class="inline-flex items-center text-sm text-emerald-600 hover:text-emerald-800 font-medium">
                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Vertrag anlegen
                </a>
            </div>
        @endif
    </div>

    {{-- Vertragshistorie --}}
    <div class="lg:col-span-2 bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-5 py-4 border-b border-gray-200">
            <h3 class="text-sm font-semibold text-gray-900">Verträge ({{ $stellplatz->vertraege->count() }})</h3>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($stellplatz->vertraege as $vertrag)
                <a href="{{ route('vertraege.show', $vertrag) }}"
                   class="px-5 py-4 flex items-center justify-between hover:bg-gray-50 transition-colors">
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $vertrag->paechter->voller_name }}</p>
                        <p class="text-xs text-gray-500">
                            {{ $vertrag->beginn->format('d.m.Y') }} –
                            {{ $vertrag->ende ? $vertrag->ende->format('d.m.Y') : 'unbefristet' }}
                            · {{ number_format($vertrag->jahresbetrag, 2, ',', '.') }} €/Jahr
                        </p>
                    </div>
                    @php $badge = ['aktiv'=>'green','gekuendigt'=>'yellow','beendet'=>'gray'][$vertrag->status] ?? 'gray'; @endphp
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-{{ $badge }}-100 text-{{ $badge }}-800">
                        {{ ucfirst($vertrag->status) }}
                    </span>
                </a>
            @empty
                <p class="px-5 py-8 text-center text-sm text-gray-500">Noch keine Verträge für diesen Stellplatz.</p>
            @endforelse
        </div>
    </div>

</div>
@endsection
