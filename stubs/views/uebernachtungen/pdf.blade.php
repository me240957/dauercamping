<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <title>{{ $titel }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10pt;
            color: #1f2937;
            background: #fff;
        }

        /* ── Header ── */
        .header {
            border-bottom: 2px solid #059669;
            padding-bottom: 10px;
            margin-bottom: 16px;
        }
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        .header h1 {
            font-size: 16pt;
            font-weight: bold;
            color: #065f46;
        }
        .header .meta {
            font-size: 8pt;
            color: #6b7280;
            text-align: right;
        }

        /* ── Tabelle ── */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        thead tr {
            background-color: #064e3b;
            color: #fff;
        }
        thead th {
            padding: 7px 8px;
            font-size: 8pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            text-align: left;
            white-space: nowrap;
        }
        thead th.right { text-align: right; }
        thead th.center { text-align: center; }

        tbody tr:nth-child(even) { background-color: #f0fdf4; }
        tbody tr:nth-child(odd)  { background-color: #ffffff; }

        tbody td {
            padding: 5px 8px;
            font-size: 9pt;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: middle;
        }
        tbody td.right  { text-align: right; }
        tbody td.center { text-align: center; }
        tbody td.bold   { font-weight: bold; }
        tbody td.green  { color: #065f46; font-weight: bold; }

        /* ── Footer / Summenzeile ── */
        tfoot tr {
            background-color: #d1fae5;
            border-top: 2px solid #059669;
        }
        tfoot td {
            padding: 7px 8px;
            font-size: 9pt;
            font-weight: bold;
            color: #065f46;
        }
        tfoot td.center { text-align: center; }
        tfoot td.right  { text-align: right; }

        /* ── Seiten-Footer ── */
        .page-footer {
            margin-top: 14px;
            font-size: 8pt;
            color: #9ca3af;
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="header-top">
            <h1>{{ $titel }}</h1>
            <div class="meta">
                Dauercamping – Verwaltung<br>
                Exportiert am {{ now()->format('d.m.Y') }}<br>
                {{ $uebernachtungen->count() }} Einträge
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Anreise</th>
                <th>Abreise</th>
                <th>Pächter</th>
                <th>Stellplatz</th>
                <th class="center">Nächte</th>
                <th class="center">Personen</th>
                <th class="center">Personennächte</th>
                <th>Notizen</th>
            </tr>
        </thead>
        <tbody>
            @forelse($uebernachtungen as $u)
                <tr>
                    <td class="bold">{{ $u->datum->format('d.m.Y') }}</td>
                    <td>{{ $u->abreisedatum?->format('d.m.Y') ?? '–' }}</td>
                    <td>{{ $u->vertrag?->paechter?->voller_name ?? '–' }}</td>
                    <td class="center">{{ $u->vertrag?->stellplatz?->nummer ?? '–' }}</td>
                    <td class="center green">{{ $u->anzahl_naechte }}</td>
                    <td class="center">{{ $u->anzahl_personen }}</td>
                    <td class="center">{{ $u->personennaechte }}</td>
                    <td style="font-size:8pt; color:#6b7280;">{{ $u->notizen ?? '' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align:center; padding:20px; color:#9ca3af;">
                        Keine Einträge gefunden.
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if($uebernachtungen->count() > 0)
        <tfoot>
            <tr>
                <td colspan="4">Gesamt</td>
                <td class="center">{{ $summe['naechte'] }}</td>
                <td class="center">{{ $summe['personen'] }}</td>
                <td class="center">{{ $summe['personennaechte'] }}</td>
                <td></td>
            </tr>
        </tfoot>
        @endif
    </table>

    <div class="page-footer">
        <span>Dauercamping Verwaltung</span>
        <span>{{ now()->format('d.m.Y H:i') }} Uhr</span>
    </div>

</body>
</html>
