<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="utf-8">
<title>Factuur {{ $factuur->number }}</title>
<style>
    @php
        $kleuren = config('theme.colors');
        $landen = ['NL' => 'Nederland', 'BE' => 'België'];
    @endphp
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'DejaVu Sans', sans-serif; font-size: 10.5px; color: {{ $kleuren['dark'] }}; }
    .pagina { padding: 46px 52px; }

    .kop { width: 100%; margin-bottom: 40px; }
    .kop td { vertical-align: top; }
    .logo { height: 52px; }
    .factuur-titel { text-align: right; }
    .factuur-titel h1 { font-size: 26px; font-weight: normal; letter-spacing: 4px; color: {{ $kleuren['primary-deep'] }}; text-transform: uppercase; }
    .factuur-titel p { margin-top: 5px; font-size: 11px; color: {{ $kleuren['dark-soft'] }}; }

    .blokken { width: 100%; margin-bottom: 34px; }
    .blokken td { vertical-align: top; width: 33%; padding-right: 20px; }
    .blok-label { font-size: 8px; font-weight: bold; letter-spacing: 2px; text-transform: uppercase; color: {{ $kleuren['primary-deep'] }}; margin-bottom: 7px; }
    .blokken p { line-height: 1.65; color: {{ $kleuren['dark-soft'] }}; }
    .blokken b { color: {{ $kleuren['dark'] }}; font-weight: bold; }

    table.artikelen { width: 100%; border-collapse: collapse; margin-bottom: 26px; }
    table.artikelen thead th { font-size: 8px; font-weight: bold; letter-spacing: 2px; text-transform: uppercase; color: {{ $kleuren['primary-deep'] }}; text-align: left; padding: 0 10px 9px 10px; border-bottom: 1.4px solid {{ $kleuren['primary'] }}; }
    table.artikelen thead th.recht, table.artikelen tbody td.recht { text-align: right; }
    table.artikelen tbody td { padding: 10px; border-bottom: 0.7px solid {{ $kleuren['bg-deep'] }}; line-height: 1.5; }
    table.artikelen tbody tr:nth-child(even) { background: {{ $kleuren['bg'] }}; }

    table.totalen { width: 250px; margin-left: auto; margin-right: 0; border-collapse: collapse; }
    table.totalen td { padding: 5px 10px; }
    table.totalen td.recht { text-align: right; }
    table.totalen tr.subtiel td { color: {{ $kleuren['dark-soft'] }}; }
    table.totalen tr.totaal td { border-top: 1.4px solid {{ $kleuren['primary'] }}; padding-top: 9px; font-size: 13.5px; font-weight: bold; }
    table.totalen tr.btw td { font-size: 9px; color: {{ $kleuren['dark-soft'] }}; }

    .betaald-stempel { display: inline-block; margin-top: 26px; padding: 7px 16px; border: 1.4px solid {{ $kleuren['primary'] }}; border-radius: 999px; color: {{ $kleuren['primary-deep'] }}; font-size: 9px; font-weight: bold; letter-spacing: 2px; text-transform: uppercase; }

    .voet { position: fixed; bottom: 30px; left: 52px; right: 52px; border-top: 0.7px solid {{ $kleuren['bg-deep'] }}; padding-top: 12px; font-size: 8.5px; color: {{ $kleuren['dark-soft'] }}; line-height: 1.7; text-align: center; }
</style>
</head>
<body>
<div class="pagina">

    <table class="kop">
        <tr>
            <td><img src="{{ public_path('logo/deluxenailshop_transp_primair_v1.png') }}" alt="{{ $bedrijf['naam'] }}" class="logo"></td>
            <td class="factuur-titel">
                <h1>Factuur</h1>
                <p><b>{{ $factuur->number }}</b></p>
                <p>Factuurdatum: {{ $factuur->created_at->translatedFormat('j F Y') }}</p>
            </td>
        </tr>
    </table>

    <table class="blokken">
        <tr>
            <td>
                <div class="blok-label">Factuur aan</div>
                <p><b>{{ $order->name }}</b>@if ($order->address)<br>{{ $order->address }}<br>{{ $order->postcode }} {{ $order->city }}<br>{{ $landen[$order->country] ?? $order->country }}@endif<br>{{ $order->email }}</p>
            </td>
            <td>
                <div class="blok-label">Bestelling</div>
                <p>Bestelnummer: <b>{{ $order->nummer() }}</b><br>Besteldatum: {{ $order->created_at->translatedFormat('j F Y') }}<br>Levering: {{ $order->levering === 'afhalen' ? 'Afhalen' : 'Bezorgen' }}<br>Betaalwijze: {{ $order->mollie_payment_id ? 'Mollie (online)' : 'Handmatig' }}</p>
            </td>
            <td>
                <div class="blok-label">{{ $bedrijf['naam'] }}</div>
                <p>{{ $bedrijf['site'] }}<br>{{ $bedrijf['email'] }}@if ($bedrijf['kvk'])<br>KVK: {{ $bedrijf['kvk'] }}@endif @if ($bedrijf['btw'])<br>BTW: {{ $bedrijf['btw'] }}@endif</p>
            </td>
        </tr>
    </table>

    <table class="artikelen">
        <thead>
            <tr>
                <th>Artikel</th>
                <th class="recht">Aantal</th>
                <th class="recht">Stukprijs</th>
                <th class="recht">Bedrag</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $regel)
                <tr>
                    <td>{{ $regel->name }}</td>
                    <td class="recht">{{ $regel->qty }}</td>
                    <td class="recht">€ {{ number_format($regel->price, 2, ',', '.') }}</td>
                    <td class="recht">€ {{ number_format($regel->price * $regel->qty, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totalen">
        <tr class="subtiel">
            <td>Subtotaal</td>
            <td class="recht">€ {{ number_format($subtotaal, 2, ',', '.') }}</td>
        </tr>
        @if ((float) $order->discount > 0)
            <tr class="subtiel">
                <td>Korting ({{ $order->discount_code }})</td>
                <td class="recht">- € {{ number_format($order->discount, 2, ',', '.') }}</td>
            </tr>
        @endif
        <tr class="subtiel">
            <td>{{ $order->levering === 'afhalen' ? 'Afhalen' : 'Verzendkosten' }}</td>
            <td class="recht">{{ (float) $order->shipping === 0.0 ? 'Gratis' : '€ '.number_format($order->shipping, 2, ',', '.') }}</td>
        </tr>
        <tr class="totaal">
            <td>Totaal</td>
            <td class="recht">€ {{ number_format($order->total, 2, ',', '.') }}</td>
        </tr>
        <tr class="btw">
            <td>Waarvan BTW (21%)</td>
            <td class="recht">€ {{ number_format($btw, 2, ',', '.') }}</td>
        </tr>
    </table>

    <div class="betaald-stempel">Betaald - bedankt voor je bestelling</div>

    <div class="voet">
        {{ $bedrijf['naam'] }} · {{ $bedrijf['site'] }} · {{ $bedrijf['email'] }}@if ($bedrijf['kvk']) · KVK {{ $bedrijf['kvk'] }}@endif @if ($bedrijf['btw']) · BTW {{ $bedrijf['btw'] }}@endif<br>
        Alle bedragen zijn inclusief BTW. Vragen over deze factuur? Mail ons gerust.
    </div>
</div>
</body>
</html>
