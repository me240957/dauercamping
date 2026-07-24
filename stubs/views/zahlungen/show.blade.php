@extends('layouts.app')
@section('title', 'Zahlung #' . $zahlung->id)
@section('page-title', 'Zahlung – ' . $zahlung->vertrag->paechter->voller_name . ' · ' . $zahlung->jahr)
@section('header-actions')
    <a href="{{ route('zahlungen.index') }}" class="text-sm text-gray-500 hover:text-gray-700 mr-2">← Zurück</a>
    <a href="{{ route('zahlungen.edit', $zahlung) }}"
       class="inline-flex items-center px-3 py-1.5 bg-emerald-600 text-white text-sm font-medium rounded-md hover:bg-emerald-700 transition-colors">
        Bearbeiten
    </a>
@endsection
@section('content')
<div class="max-w-lg">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <dl class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <dt class="text-xs text-gray-500">Pächter</dt>
                    <dd class="text-sm font-medium text-gray-900">
                        <a href="{{ route('paechter.show', $zahlung->vertrag->paechter) }}" class="text-emerald-600 hover:underline">
                            {{ $zahlung->vertrag->paechter->voller_name }}
                        </a>
                    </dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-500">Stellplatz</dt>
                    <dd class="text-sm font-medium text-gray-900">
                        <a href="{{ route('stellplaetze.show', $zahlung->vertrag->stellplatz) }}" class="text-emerald-600 hover:underline">
                            {{ $zahlung->vertrag->stellplatz->nummer }}
                        </a>
                    </dd>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <dt class="text-xs text-gray-500">Abrechnungsjahr</dt>
                    <dd class="text-2xl font-bold text-gray-900">{{ $zahlung->jahr }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-500">Betrag</dt>
                    <dd class="text-2xl font-bold text-gray-900">{{ number_format($zahlung->betrag, 2, ',', '.') }} €</dd>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <dt class="text-xs text-gray-500">Fällig am</dt>
                    <dd class="text-sm {{ $zahlung->ist_ueberfaellig ? 'text-red-600 font-semibold' : 'text-gray-900' }}">
                        {{ $zahlung->faellig_am ? $zahlung->faellig_am->format('d.m.Y') : '–' }}
                        @if($zahlung->ist_ueberfaellig) <span class="text-xs font-normal">(überfällig)</span> @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-500">Bezahlt am</dt>
                    <dd class="text-sm text-gray-900">{{ $zahlung->bezahlt_am ? $zahlung->bezahlt_am->format('d.m.Y') : '–' }}</dd>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <dt class="text-xs text-gray-500">Status</dt>
                    <dd>
                        @php $badge = ['bezahlt'=>'green','offen'=>'yellow','gemahnt'=>'red','storniert'=>'gray'][$zahlung->status] ?? 'gray'; @endphp
                        <span class="inline-flex items-center px-2.5 py-1 rounded text-sm font-medium bg-{{ $badge }}-100 text-{{ $badge }}-800">
                            {{ $zahlung->status_label }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-500">Zahlungsart</dt>
                    <dd class="text-sm text-gray-900">{{ $zahlung->zahlungsart ?: '–' }}</dd>
                </div>
            </div>
            @if($zahlung->referenz)
            <div>
                <dt class="text-xs text-gray-500">Referenz / Verwendungszweck</dt>
                <dd class="text-sm text-gray-900">{{ $zahlung->referenz }}</dd>
            </div>
            @endif
            @if($zahlung->notizen)
            <div>
                <dt class="text-xs text-gray-500">Notizen</dt>
                <dd class="text-sm text-gray-700 whitespace-pre-line">{{ $zahlung->notizen }}</dd>
            </div>
            @endif
        </dl>

        @if($zahlung->status === 'offen')
            <div class="mt-6 pt-4 border-t border-gray-100">
                <form method="POST" action="{{ route('zahlungen.bezahlt', $zahlung) }}">
                    @csrf @method('PATCH')
                    <button type="submit"
                            class="px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-md hover:bg-emerald-700 transition-colors">
                        Als bezahlt markieren
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection
