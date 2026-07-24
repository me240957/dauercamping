@extends('layouts.app')
@section('title', $paechter->voller_name)
@section('page-title', $paechter->voller_name)
@section('header-actions')
    <a href="{{ route('paechter.index') }}" class="text-sm text-gray-500 hover:text-gray-700 mr-2">← Zurück</a>
    <a href="{{ route('paechter.edit', $paechter) }}"
       class="inline-flex items-center px-3 py-1.5 bg-emerald-600 text-white text-sm font-medium rounded-md hover:bg-emerald-700 transition-colors">
        Bearbeiten
    </a>
@endsection
@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 lg:col-span-1">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Stammdaten</h3>
        <dl class="space-y-3">
            @if($paechter->email)
            <div><dt class="text-xs text-gray-500">E-Mail</dt><dd class="text-sm text-gray-900">{{ $paechter->email }}</dd></div>
            @endif
            @if($paechter->telefon)
            <div><dt class="text-xs text-gray-500">Telefon</dt><dd class="text-sm text-gray-900">{{ $paechter->telefon }}</dd></div>
            @endif
            @if($paechter->mobil)
            <div><dt class="text-xs text-gray-500">Mobil</dt><dd class="text-sm text-gray-900">{{ $paechter->mobil }}</dd></div>
            @endif
            @if($paechter->adresse)
            <div><dt class="text-xs text-gray-500">Adresse</dt><dd class="text-sm text-gray-900 whitespace-pre-line">{{ str_replace(', ', "\n", $paechter->adresse) }}</dd></div>
            @endif
            @if($paechter->geburtsdatum)
            <div><dt class="text-xs text-gray-500">Geburtsdatum</dt><dd class="text-sm text-gray-900">{{ $paechter->geburtsdatum->format('d.m.Y') }}</dd></div>
            @endif
            <div>
                <dt class="text-xs text-gray-500">Status</dt>
                <dd>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $paechter->status === 'aktiv' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                        {{ ucfirst($paechter->status) }}
                    </span>
                </dd>
            </div>
            @if($paechter->notizen)
            <div><dt class="text-xs text-gray-500">Notizen</dt><dd class="text-sm text-gray-700 whitespace-pre-line">{{ $paechter->notizen }}</dd></div>
            @endif
        </dl>
    </div>
    <div class="lg:col-span-2 bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-5 py-4 border-b border-gray-200">
            <h3 class="text-sm font-semibold text-gray-900">Verträge ({{ $paechter->vertraege->count() }})</h3>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($paechter->vertraege as $vertrag)
                <a href="{{ route('vertraege.show', $vertrag) }}"
                   class="px-5 py-4 flex items-center justify-between hover:bg-gray-50 transition-colors">
                    <div>
                        <p class="text-sm font-medium text-gray-900">Stellplatz {{ $vertrag->stellplatz->nummer }}</p>
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
                <p class="px-5 py-8 text-center text-sm text-gray-500">Noch keine Verträge.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
