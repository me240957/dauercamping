@extends('layouts.app')
@section('title', 'Vertrag #' . $vertrag->id)
@section('page-title', 'Vertrag #' . $vertrag->id . ' – ' . ($vertrag->paechter?->voller_name ?? '–') . ' / Stellplatz ' . ($vertrag->stellplatz?->nummer ?? '–'))
@section('header-actions')
    <a href="{{ route('vertraege.index') }}" class="text-sm text-gray-500 hover:text-gray-700 mr-2">← Zurück</a>
    <a href="{{ route('vertraege.edit', $vertrag) }}"
       class="inline-flex items-center px-3 py-1.5 bg-emerald-600 text-white text-sm font-medium rounded-md hover:bg-emerald-700 transition-colors">
        Bearbeiten
    </a>
@endsection
@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Vertragsdetails</h3>
        <dl class="space-y-3">
            <div><dt class="text-xs text-gray-500">Stellplatz</dt><dd class="text-sm font-medium text-gray-900"><a href="{{ route('stellplaetze.show', $vertrag->stellplatz) }}" class="text-emerald-600 hover:underline">{{ $vertrag->stellplatz->nummer }}</a></dd></div>
            <div><dt class="text-xs text-gray-500">Pächter</dt><dd class="text-sm font-medium text-gray-900"><a href="{{ route('paechter.show', $vertrag->paechter) }}" class="text-emerald-600 hover:underline">{{ $vertrag->paechter->voller_name }}</a></dd></div>
            <div><dt class="text-xs text-gray-500">Beginn</dt><dd class="text-sm text-gray-900">{{ $vertrag->beginn->format('d.m.Y') }}</dd></div>
            <div><dt class="text-xs text-gray-500">Ende</dt><dd class="text-sm text-gray-900">{{ $vertrag->ende ? $vertrag->ende->format('d.m.Y') : 'unbefristet' }}</dd></div>
            <div><dt class="text-xs text-gray-500">Jahresbetrag</dt><dd class="text-sm font-semibold text-gray-900">{{ number_format($vertrag->jahresbetrag, 2, ',', '.') }} €</dd></div>
            <div><dt class="text-xs text-gray-500">Zahlungsrhythmus</dt><dd class="text-sm text-gray-900">{{ $vertrag->zahlungsrhythmus_label }}</dd></div>
            <div>
                <dt class="text-xs text-gray-500">Status</dt>
                <dd>
                    @php $badge = ['aktiv'=>'green','gekuendigt'=>'yellow','beendet'=>'gray'][$vertrag->status] ?? 'gray'; @endphp
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-{{ $badge }}-100 text-{{ $badge }}-800">
                        {{ ucfirst($vertrag->status) }}
                    </span>
                </dd>
            </div>
            @if($vertrag->notizen)
            <div><dt class="text-xs text-gray-500">Notizen</dt><dd class="text-sm text-gray-700 whitespace-pre-line">{{ $vertrag->notizen }}</dd></div>
            @endif
        </dl>
        <div class="mt-5 pt-4 border-t border-gray-100">
            <a href="{{ route('zahlungen.create', ['vertrag_id' => $vertrag->id]) }}"
               class="inline-flex items-center text-sm text-emerald-600 hover:text-emerald-800 font-medium">
                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Zahlung anlegen
            </a>
        </div>
    </div>
    <div class="lg:col-span-2 bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-5 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-sm font-semibold text-gray-900">Zahlungen ({{ $vertrag->zahlungen->count() }})</h3>
            <span class="text-xs text-gray-500">Offen: {{ number_format($vertrag->offene_betrag, 2, ',', '.') }} €</span>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($vertrag->zahlungen->sortByDesc('jahr') as $zahlung)
                <div class="px-5 py-3 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $zahlung->jahr }}</p>
                        <p class="text-xs text-gray-500">
                            @if($zahlung->faellig_am) Fällig: {{ $zahlung->faellig_am->format('d.m.Y') }} @endif
                            @if($zahlung->bezahlt_am) · Bezahlt: {{ $zahlung->bezahlt_am->format('d.m.Y') }} @endif
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-semibold text-gray-900">{{ number_format($zahlung->betrag, 2, ',', '.') }} €</span>
                        @php $badge = ['bezahlt'=>'green','offen'=>'yellow','gemahnt'=>'red','storniert'=>'gray'][$zahlung->status] ?? 'gray'; @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-{{ $badge }}-100 text-{{ $badge }}-800">
                            {{ $zahlung->status_label }}
                        </span>
                        @if($zahlung->status === 'offen')
                            <form method="POST" action="{{ route('zahlungen.bezahlt', $zahlung) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="text-xs text-emerald-600 hover:text-emerald-800 font-medium">Als bezahlt markieren</button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <p class="px-5 py-8 text-center text-sm text-gray-500">Noch keine Zahlungen erfasst.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
