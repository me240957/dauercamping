@extends('layouts.app')
@section('title', 'Übernachtungsstatistik ' . $jahr)
@section('page-title', 'Übernachtungsstatistik ' . $jahr)

@section('header-actions')
    <form method="GET" action="{{ route('uebernachtungen.statistik') }}" class="flex items-center gap-2">
        <select name="jahr" onchange="this.form.submit()"
                class="border border-gray-300 rounded-md text-sm px-3 py-1.5 focus:ring-emerald-500 focus:border-emerald-500">
            @foreach($verfuegbareJahre as $j)
                <option value="{{ $j }}" @selected($j == $jahr)>{{ $j }}</option>
            @endforeach
            @if($verfuegbareJahre->isEmpty())
                <option value="{{ now()->year }}">{{ now()->year }}</option>
            @endif
        </select>
        <a href="{{ route('uebernachtungen.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Erfassung</a>
    </form>
@endsection

@section('content')

@php $maxNaechte = $proMonat->max('naechte') ?: 1; @endphp

{{-- Jahresgesamtkarten --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 text-center">
        <p class="text-xs font-medium text-gray-500 mb-1">Einträge</p>
        <p class="text-3xl font-bold text-gray-900">{{ $gesamt->eintraege ?? 0 }}</p>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 text-center">
        <p class="text-xs font-medium text-gray-500 mb-1">Nächte gesamt</p>
        <p class="text-3xl font-bold text-emerald-600">{{ $gesamt->naechte_gesamt ?? 0 }}</p>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 text-center">
        <p class="text-xs font-medium text-gray-500 mb-1">Personen gesamt</p>
        <p class="text-3xl font-bold text-blue-600">{{ $gesamt->personen_gesamt ?? 0 }}</p>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 text-center">
        <p class="text-xs font-medium text-gray-500 mb-1">Personennächte</p>
        <p class="text-3xl font-bold text-purple-600">{{ $gesamt->personennaechte_gesamt ?? 0 }}</p>
    </div>
</div>

{{-- Monatsübersicht (Balkendiagramm) --}}
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 mb-6">
    <h3 class="text-sm font-semibold text-gray-900 mb-4">Nächte pro Monat</h3>
    <div class="flex items-end gap-2 h-40">
        @foreach($monate as $nr => $name)
            @php
                $data = $proMonat->get($nr);
                $naechte = $data->naechte ?? 0;
                $hoehe = $maxNaechte > 0 ? round(($naechte / $maxNaechte) * 100) : 0;
            @endphp
            <div class="flex-1 flex flex-col items-center gap-1">
                <span class="text-xs font-semibold text-gray-700">{{ $naechte ?: '' }}</span>
                <div class="w-full bg-emerald-500 rounded-t transition-all"
                     style="height: {{ max($hoehe, $naechte > 0 ? 4 : 0) }}%;"
                     title="{{ $name }}: {{ $naechte }} Nächte, {{ $data->personennaechte ?? 0 }} Personennächte">
                </div>
                <span class="text-xs text-gray-400">{{ substr($name, 0, 3) }}</span>
            </div>
        @endforeach
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Nächte pro Stellplatz --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-5 py-4 border-b border-gray-200">
            <h3 class="text-sm font-semibold text-gray-900">Nächte pro Stellplatz</h3>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($proStellplatz as $row)
                @php $sp = $stellplatzMap->get($row->stellplatz_id); @endphp
                <div class="px-5 py-3 flex items-center justify-between">
                    <div class="flex items-center gap-3 flex-1">
                        <span class="text-sm font-semibold text-gray-900 w-12">
                            {{ $sp?->nummer ?? '–' }}
                        </span>
                        <div class="flex-1 bg-gray-100 rounded-full h-2">
                            <div class="bg-emerald-500 h-2 rounded-full"
                                 style="width: {{ $proStellplatz->max('naechte') > 0 ? round(($row->naechte / $proStellplatz->max('naechte')) * 100) : 0 }}%">
                            </div>
                        </div>
                    </div>
                    <div class="text-right ml-4">
                        <span class="text-sm font-bold text-gray-900">{{ $row->naechte }}</span>
                        <span class="text-xs text-gray-400 ml-1">Nächte</span>
                        <div class="text-xs text-gray-400">{{ $row->personennaechte }} Personennächte</div>
                    </div>
                </div>
            @empty
                <p class="px-5 py-8 text-sm text-gray-500 text-center">Keine Daten für {{ $jahr }}</p>
            @endforelse
        </div>
    </div>

    {{-- Nächte pro Pächter (Top 10) --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-5 py-4 border-b border-gray-200">
            <h3 class="text-sm font-semibold text-gray-900">Aktivste Pächter (Top 10)</h3>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($proPaechter as $i => $row)
                @php $p = $paechterMap->get($row->paechter_id); @endphp
                <div class="px-5 py-3 flex items-center justify-between">
                    <div class="flex items-center gap-3 flex-1">
                        <span class="text-xs font-bold text-gray-400 w-5">{{ $i + 1 }}.</span>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ $p?->voller_name ?? '–' }}</p>
                            <div class="mt-1 bg-gray-100 rounded-full h-1.5">
                                <div class="bg-blue-500 h-1.5 rounded-full"
                                     style="width: {{ $proPaechter->max('naechte') > 0 ? round(($row->naechte / $proPaechter->max('naechte')) * 100) : 0 }}%">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-right ml-4">
                        <span class="text-sm font-bold text-gray-900">{{ $row->naechte }}</span>
                        <span class="text-xs text-gray-400 ml-1">Nächte</span>
                        <div class="text-xs text-gray-400">{{ $row->personennaechte }} Personennächte</div>
                    </div>
                </div>
            @empty
                <p class="px-5 py-8 text-sm text-gray-500 text-center">Keine Daten für {{ $jahr }}</p>
            @endforelse
        </div>
    </div>

</div>

{{-- Monatstabelle --}}
<div class="bg-white rounded-lg shadow-sm border border-gray-200 mt-6">
    <div class="px-5 py-4 border-b border-gray-200">
        <h3 class="text-sm font-semibold text-gray-900">Monatsdetails {{ $jahr }}</h3>
    </div>
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Monat</th>
                <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Nächte</th>
                <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Personen</th>
                <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Personennächte</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($monate as $nr => $name)
                @php $data = $proMonat->get($nr); @endphp
                <tr class="{{ $data ? 'bg-white' : 'bg-gray-50' }}">
                    <td class="px-5 py-2 text-sm {{ $data ? 'font-medium text-gray-900' : 'text-gray-400' }}">{{ $name }}</td>
                    <td class="px-5 py-2 text-sm text-right {{ $data ? 'font-semibold text-emerald-700' : 'text-gray-300' }}">
                        {{ $data->naechte ?? '–' }}
                    </td>
                    <td class="px-5 py-2 text-sm text-right {{ $data ? 'text-gray-900' : 'text-gray-300' }}">
                        {{ $data->personen ?? '–' }}
                    </td>
                    <td class="px-5 py-2 text-sm text-right {{ $data ? 'font-semibold text-purple-700' : 'text-gray-300' }}">
                        {{ $data->personennaechte ?? '–' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot class="bg-emerald-50 border-t-2 border-emerald-200">
            <tr>
                <td class="px-5 py-3 text-sm font-bold text-gray-900">Gesamt</td>
                <td class="px-5 py-3 text-sm font-bold text-emerald-700 text-right">{{ $gesamt->naechte_gesamt ?? 0 }}</td>
                <td class="px-5 py-3 text-sm font-bold text-gray-900 text-right">{{ $gesamt->personen_gesamt ?? 0 }}</td>
                <td class="px-5 py-3 text-sm font-bold text-purple-700 text-right">{{ $gesamt->personennaechte_gesamt ?? 0 }}</td>
            </tr>
        </tfoot>
    </table>
</div>

@endsection
