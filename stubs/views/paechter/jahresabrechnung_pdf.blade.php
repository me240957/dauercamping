<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <title>Jahresabrechnung {{ $jahr }} – {{ $paechter->voller_name }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9.5pt;
            color: #1f2937;
            background: #fff;
            line-height: 1.4;
        }

        /* ── Header ── */
        .header {
            border-bottom: 3px solid #059669;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .header h1 {
            font-size: 17pt;
            font-weight: bold;
            color: #065f46;
        }
        .header .subtitle {
            font-size: 10pt;
            color: #6b7280;
            margin-top: 2px;
        }
        .header .meta {
            font-size: 8pt;
            color: #9ca3af;
            text-align: right;
            line-height: 1.6;
        }

        /* ── Pächter-Block ── */
        .paechter-box {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 4px;
            padding: 12px 14px;
            margin-bottom: 18px;
            display: flex;
            justify-content: space-between;
        }
        .paechter-box h2 {
            font-size: 12pt;
            font-weight: bold;
            color: #065f46;
            margin-bottom: 4px;
        }
        .paechter-box .label {
            font-size: 7.5pt;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .paechter-box .value {
            font-size: 9pt;
            color: #1f2937;
        }

        /* ── Abschnitt ── */
        .section {
            margin-bottom: 22px;
        }
        .section-title {
            font-size: 10pt;
            font-weight: bold;
            color: #065f46;
            background: #ecfdf5;
            border-left: 3px solid #059669;
            padding: 5px 10px;
            margin-bottom: 8px;
        }

        /* ── Tabellen ── */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }
        thead tr {
            background: #064e3b;
            color: #fff;
        }
        thead th {
            padding: 5px 8px;
            font-size: 7.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            text-align: left;
        }
        thead th.right { text-align: right; }
        thead th.center { text-align: center; }

        tbody tr:nth-child(even) { background: #f0fdf4; }
        tbody tr:nth-child(odd)  { background: #fff; }
        tbody td {
            padding: 4px 8px;
            font-size: 9pt;
            border-bottom: 1px solid #e5e7eb;
        }
        tbody td.right  { text-align: right; }
        tbody td.center { text-align: center; }
        tbody td.bold   { font-weight: bold; }
        tbody td.green  { color: #065f46; font-weight: bold; }
        tbody td.red    { color: #dc2626; }
        tbody td.gray   { color: #9ca3af; }

        tfoot tr { background: #d1fae5; border-top: 2px solid #059669; }
        tfoot td {
            padding: 5px 8px;
            font-size: 9pt;
            font-weight: bold;
            color: #065f46;
        }
        tfoot td.right  { text-align: right; }
        tfoot td.center { text-align: center; }

        /* ── Statusbadge ── */
        .badge-green { color: #065f46; font-weight: bold; }
        .badge-red   { color: #dc2626; font-weight: bold; }
        .badge-gray  { color: #6b7280; }

        /* ── Vertrag-Header ── */
        .vertrag-header {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 3px;
            padding: 8px 12px;
            margin-bottom: 8px;
            display: flex;
            justify-content: space-between;
        }
        .vertrag-header .sp {
            font-size: 11pt;
            font-weight: bold;
            color: #1f2937;
        }
        .vertrag-header .detail {
            font-size: 8pt;
            color: #6b7280;
            margin-top: 2px;
        }
        .vertrag-header .betrag {
            font-size: 11pt;
            font-weight: bold;
            color: #059669;
            text-align: right;
        }

        /* ── Zusammenfassung ── */
        .summary {
            background: #064e3b;
            color: #fff;
            border-radius: 4px;
            padding: 12px 16px;
            margin-top: 20px;
        }
        .summary h3 {
            font-size: 10pt;
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 1px solid #065f46;
            padding-bottom: 5px;
        }
        .summary-grid {
            display: flex;
            gap: 0;
        }
        .summary-item {
            flex: 1;
            text-align: center;
            padding: 0 8px;
            border-right: 1px solid #065f46;
        }
        .summary-item:last-child { border-right: none; }
        .summary-item .s-label {
            font-size: 7.5pt;
            color: #6ee7b7;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .summary-item .s-value {
            font-size: 14pt;
            font-weight: bold;
            color: #fff;
            margin-top: 2px;
        }
        .summary-item .s-sub {
            font-size: 7.5pt;
            color: #a7f3d0;
        }

        /* ── Footer ── */
        .page-footer {
            margin-top: 16px;
            font-size: 7.5pt;
            color: #9ca3af;
            display: flex;
            justify-content: space-between;
            border-top: 1px solid #e5e7eb;
            padding-top: 6px;
        }

        .no-data {
            text-align: center;
            color: #9ca3af;
            font-style: italic;
            padding: 8px;
            font-size: 8.5pt;
        }

        .page-break { page-break-before: always; }
    </style>
</head>
<body>

{{-- ── Header ── --}}
<div class="header">
    <div class="header-top">
        <div>
            <h1>Jahresabrechnung {{ $jahr }}</h1>
            <p class="subtitle">Dauercamping – Verwaltung</p>
        </div>
        <div class="meta">
            Erstellt am {{ now()->format('d.m.Y') }}<br>
            Abrechnungsjahr {{ $jahr }}
        </div>
    </div>
</div>

{{-- ── Pächter-Info ── --}}
<div class="paechter-box">
    <div>
        <h2>{{ $paechter->voller_name }}</h2>
        @if($paechter->strasse || $paechter->ort)
            <p class="value">
                {{ $paechter->strasse }}
                @if($paechter->strasse && ($paechter->plz || $paechter->ort)) · @endif
                {{ $paechter->plz }} {{ $paechter->ort }}
            </p>
        @endif
    </div>
    <div style="text-align: right;">
        @if($paechter->email)
            <p><span class="label">E-Mail</span><br><span class="value">{{ $paechter->email }}</span></p>
        @endif
        @if($paechter->telefon || $paechter->mobil)
            <p style="margin-top:4px;"><span class="label">Telefon</span><br>
            <span class="value">{{ $paechter->telefon ?: $paechter->mobil }}</span></p>
        @endif
    </div>
</div>

{{-- ── Verträge ── --}}
@forelse($vertraege as $vertrag)

<div class="section">
    {{-- Vertrag-Header --}}
    <div class="vertrag-header">
        <div>
            <p class="sp">Stellplatz {{ $vertrag->stellplatz?->nummer ?? '–' }}</p>
            <p class="detail">
                Laufzeit: {{ $vertrag->beginn?->format('d.m.Y') }} –
                {{ $vertrag->ende ? $vertrag->ende->format('d.m.Y') : 'unbefristet' }}
                &nbsp;·&nbsp; {{ $vertrag->zahlungsrhythmus_label }}
            </p>
        </div>
        <div>
            <p class="betrag">{{ number_format($vertrag->jahresbetrag, 2, ',', '.') }} €/Jahr</p>
            <p class="detail" style="text-align:right;">
                Status:
                @if($vertrag->status === 'aktiv')
                    <span class="badge-green">Aktiv</span>
                @elseif($vertrag->status === 'gekuendigt')
                    <span class="badge-red">Gekündigt</span>
                @else
                    <span class="badge-gray">{{ ucfirst($vertrag->status) }}</span>
                @endif
            </p>
        </div>
    </div>

    {{-- Zahlungen --}}
    <p class="section-title">Zahlungen {{ $jahr }}</p>
    @if($vertrag->zahlungen->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Fälligkeit</th>
                    <th>Zahlungsart</th>
                    <th class="right">Betrag</th>
                    <th class="center">Status</th>
                    <th>Bezahlt am</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vertrag->zahlungen as $z)
                    <tr>
                        <td>{{ $z->faellig_am?->format('d.m.Y') ?? '–' }}</td>
                        <td class="gray">{{ ucfirst($z->zahlungsart ?? '–') }}</td>
                        <td class="right bold">{{ number_format($z->betrag, 2, ',', '.') }} €</td>
                        <td class="center">
                            @if($z->status === 'bezahlt')
                                <span class="badge-green">✓ Bezahlt</span>
                            @elseif($z->ist_ueberfaellig)
                                <span class="badge-red">Überfällig</span>
                            @else
                                <span class="badge-gray">Offen</span>
                            @endif
                        </td>
                        <td class="gray">{{ $z->bezahlt_am?->format('d.m.Y') ?? '–' }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2">Summe Zahlungen {{ $jahr }}</td>
                    <td class="right">{{ number_format($vertrag->zahlungen->sum('betrag'), 2, ',', '.') }} €</td>
                    <td colspan="2" style="font-size:8pt; color:#6ee7b7;">
                        davon bezahlt: {{ number_format($vertrag->zahlungen->where('status','bezahlt')->sum('betrag'), 2, ',', '.') }} €
                        &nbsp;|&nbsp;
                        offen: {{ number_format($vertrag->zahlungen->where('status','!=','bezahlt')->sum('betrag'), 2, ',', '.') }} €
                    </td>
                </tr>
            </tfoot>
        </table>
    @else
        <p class="no-data">Keine Zahlungen für {{ $jahr }} erfasst.</p>
    @endif

    {{-- Übernachtungen --}}
    <p class="section-title" style="margin-top:12px;">Übernachtungen {{ $jahr }}</p>
    @if($vertrag->uebernachtungen->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Anreise</th>
                    <th>Abreise</th>
                    <th class="center">Nächte</th>
                    <th class="center">Personen</th>
                    <th class="center">Personennächte</th>
                    <th>Notizen</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vertrag->uebernachtungen as $u)
                    <tr>
                        <td class="bold">{{ $u->datum?->format('d.m.Y') }}</td>
                        <td>{{ $u->abreisedatum?->format('d.m.Y') ?? '–' }}</td>
                        <td class="center green">{{ $u->anzahl_naechte }}</td>
                        <td class="center">{{ $u->anzahl_personen }}</td>
                        <td class="center">{{ $u->personennaechte }}</td>
                        <td class="gray" style="font-size:8pt;">{{ $u->notizen ?? '' }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2">Summe Übernachtungen {{ $jahr }}</td>
                    <td class="center">{{ $vertrag->uebernachtungen->sum('anzahl_naechte') }}</td>
                    <td class="center">{{ $vertrag->uebernachtungen->sum('anzahl_personen') }}</td>
                    <td class="center">{{ $vertrag->uebernachtungen->sum('personennaechte') }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    @else
        <p class="no-data">Keine Übernachtungen für {{ $jahr }} erfasst.</p>
    @endif
</div>

@if(!$loop->last)
    <div class="page-break"></div>
@endif

@empty
    <p style="text-align:center; color:#9ca3af; padding:30px;">
        Für {{ $paechter->voller_name }} liegen keine Verträge im Jahr {{ $jahr }} vor.
    </p>
@endforelse

{{-- ── Jahres-Zusammenfassung ── --}}
<div class="summary">
    <h3>Zusammenfassung {{ $jahr }}</h3>
    <div class="summary-grid">
        <div class="summary-item">
            <p class="s-label">Bezahlt</p>
            <p class="s-value">{{ number_format($summen['zahlungen_bezahlt'], 2, ',', '.') }} €</p>
            <p class="s-sub">Eingegangen</p>
        </div>
        <div class="summary-item">
            <p class="s-label">Offen</p>
            <p class="s-value" style="{{ $summen['zahlungen_offen'] > 0 ? 'color:#fca5a5;' : '' }}">
                {{ number_format($summen['zahlungen_offen'], 2, ',', '.') }} €
            </p>
            <p class="s-sub">Ausstehend</p>
        </div>
        <div class="summary-item">
            <p class="s-label">Nächte</p>
            <p class="s-value">{{ $summen['naechte'] }}</p>
            <p class="s-sub">Übernachtungen</p>
        </div>
        <div class="summary-item">
            <p class="s-label">Personennächte</p>
            <p class="s-value">{{ $summen['personennaechte'] }}</p>
            <p class="s-sub">Gesamt</p>
        </div>
    </div>
</div>

<div class="page-footer">
    <span>Dauercamping Verwaltung · Jahresabrechnung {{ $jahr }} · {{ $paechter->voller_name }}</span>
    <span>{{ now()->format('d.m.Y H:i') }} Uhr</span>
</div>

</body>
</html>
