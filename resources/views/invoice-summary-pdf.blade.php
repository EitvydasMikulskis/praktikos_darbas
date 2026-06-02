<!DOCTYPE html>
<html lang="lt">
<head>
<meta charset="UTF-8">

<style>
    body{
        font-family: DejaVu Sans, sans-serif;
        font-size:10px;
        color:#000;
    }

    h1{
        text-align:center;
        font-size:14px;
        margin-bottom:10px;
    }

    .period{
        text-align:center;
        margin-bottom:20px;
        font-size:11px;
    }

    table{
        width:100%;
        border-collapse:collapse;
        table-layout:fixed;
    }

    th,
    td{
        border:1px solid black;
        padding:4px;
        text-align:left;
        word-wrap:break-word;
        font-size:9px;
    }

    th{
        background:#eeeeee;
        text-align:center;
    }

    .approve{
        margin-top:40px;
        font-size:11px;
    }
</style>

</head>
<body>

@php
    $seller = \App\Models\Seller::first();
@endphp

<h1>
    {{ $seller->company_name }},
    įmonės kodas {{ $seller->company_code }},
    gaunamų sąskaitų-faktūrų registras
</h1>

<div class="period">
    Laikotarpis nuo {{ request('start_date') }} iki {{ request('end_date') }}
</div>

<table>
    <thead>
        <tr>
            <th>Eil. Nr.</th>
            <th>Išrašymo data</th>
            <th>Serija</th>
            <th>Numeris</th>
            <th>Pirkėjo pavadinimas</th>
            <th>Pirkėjo kodas</th>
            <th>Pirkėjo PVM mokėjimo kodas</th>
            <th>Suma be PVM</th>
            <th>PVM suma</th>
            <th>Viso su PVM</th>
        </tr>
    </thead>

    <tbody>
        @foreach($invoices as $index => $invoice)

            @php
                $client = \App\Models\Client::find($invoice->client_id);

                $invoiceParts = explode('-', $invoice->invoice_number);
                $series = $invoiceParts[0] ?? '';
                $number = $invoiceParts[1] ?? $invoice->invoice_number;
            @endphp

            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $invoice->created_at->format('Y-m-d') }}</td>
                <td>{{ $series }}</td>
                <td>{{ $number }}</td>
                <td>{{ $client->company_name ?? '' }}</td>
                <td>{{ $client->company_code ?? '' }}</td>
                <td>{{ $client->vat_code ?? '' }}</td>
                <td>{{ number_format($invoice->total_without_vat, 2) }}</td>
                <td>{{ number_format($invoice->vat_amount, 2) }}</td>
                <td>{{ number_format($invoice->total_with_vat, 2) }}</td>
            </tr>

        @endforeach
    </tbody>
</table>

<div class="approve">
    Tvirtinu ________________________________
</div>

</body>
</html>