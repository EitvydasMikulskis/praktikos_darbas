@php

use NumberToWords\NumberToWords;

$numberToWords = new NumberToWords();

$numberTransformer = $numberToWords->getNumberTransformer('lt');

$amount = floor($invoice->total_with_vat);

$cents = round(($invoice->total_with_vat - $amount) * 100);

$amountWords = $numberTransformer->toWords($amount);

@endphp

<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <title>Sąskaita faktūra</title>

    <style>

        body{
    background:#e5e5e5;
    margin:0;
    padding:40px;
    font-family:"Times New Roman", serif;
}

.invoice-sheet{
    background:white;
    width:900px;
    margin:auto;
    padding:50px;
    box-shadow:0 0 20px rgba(0,0,0,0.08);
}

/* Title */

.invoice-title{
    text-align:center;
    font-size:28px;
    font-weight:bold;
    margin-bottom:10px;
    letter-spacing:1px;
}

.invoice-number{
    text-align:center;
    font-size:20px;
    font-weight:bold;
    margin-bottom:10px;
}

.invoice-date{
    text-align:center;
    margin-bottom:50px;
    font-size:15px;
    color:#444;
}

/* Parties */

.invoice-parties{
    display:flex;
    justify-content:space-between;
    margin-bottom:50px;
    gap:40px;
}

.party-block{
    width:50%;
}

.party-title{
    font-size:20px;
    font-weight:bold;
    margin-bottom:18px;
    border-bottom:1px solid #ccc;
    padding-bottom:8px;
}

.party-row{
    margin-bottom:10px;
    font-size:15px;
    line-height:1.5;
}

/* Table */

table{
    width:100%;
    border-collapse:collapse;
    margin-top:30px;
}

th,
td{
    border:1px solid #000;
    padding:12px;
    text-align:center;
    font-size:14px;
}

th{
    background:#f5f5f5;
    font-weight:bold;
}

/* Totals */

.totals{
    margin-top:40px;
    width:350px;
    margin-left:auto;
}

.totals-row{
    display:flex;
    justify-content:space-between;
    margin-bottom:12px;
    font-size:17px;
}

.totals-row strong{
    font-weight:bold;
}

/* Sum in words */

.sum-words{
    margin-top:50px;
    font-size:16px;
    line-height:1.6;
}

/* Signatures */

.signature-section{
    margin-top:90px;
}

.signature-row{
    margin-bottom:50px;
    font-size:16px;
}

.signature-line{
    display:inline-block;
    width:300px;
    border-bottom:1px solid black;
    margin-left:20px;
    transform:translateY(-5px);
}

/* Print */

@media print{

    body{
        background:white;
        padding:0;
    }

    .invoice-sheet{
        box-shadow:none;
        width:100%;
        padding:20px;
    }

}
    </style>

</head>
<body>

<div class="invoice-sheet">

    <div class="invoice-title">
        PVM SĄSKAITA - FAKTŪRA
    </div>

    <div class="invoice-number">
        {{ $invoice->invoice_number }}
    </div>

    <div class="invoice-date">
        {{ $invoice->created_at->format('Y-m-d') }}
    </div>

    <div class="invoice-parties">

        <div class="party-block">

            <div class="party-title">
                Pardavėjas
            </div>

            <div class="party-row">
                <strong>Pavadinimas:</strong>
                {{ $seller->company_name }}
            </div>

            <div class="party-row">
                <strong>Įmonės kodas:</strong>
                {{ $seller->company_code }}
            </div>

            <div class="party-row">
                <strong>Adresas:</strong>
                {{ $seller->address }}
            </div>

            <div class="party-row">
                <strong>PVM kodas:</strong>
                {{ $seller->vat_code }}
            </div>

            <div class="party-row">
                <strong>Banko sąskaita:</strong>
                {{ $seller->bank_account }}
            </div>

        </div>

        <div class="party-block">

            <div class="party-title">
                Pirkėjas
            </div>

            <div class="party-row">
                <strong>Pavadinimas:</strong>
                {{ $client->company_name }}
            </div>

            <div class="party-row">
                <strong>Įmonės kodas:</strong>
                {{ $client->company_code }}
            </div>

            <div class="party-row">
                <strong>Adresas:</strong>
                {{ $client->address }}
            </div>

            <div class="party-row">
                <strong>PVM kodas:</strong>
                {{ $client->vat_code }}
            </div>

        </div>

    </div>

    <table>

        <thead>

            <tr>
                <th>Nr.</th>
                <th>Pavadinimas</th>
                <th>Kiekis</th>
                <th>Kaina</th>
                <th>Suma</th>
            </tr>

        </thead>

        <tbody>

            @foreach($items as $index => $item)

            @php
                $product = \App\Models\Product::find($item->product_id);
            @endphp

            <tr>

                <td>{{ $index + 1 }}</td>

                <td>{{ $product->product_name }}</td>

                <td>{{ $item->quantity }}</td>

                <td>{{ number_format($item->price, 2) }} €</td>

                <td>{{ number_format($item->total, 2) }} €</td>

            </tr>

            @endforeach

        </tbody>

    </table>

    <div class="totals">

        <div class="totals-row">
            <strong>Suma be PVM:</strong>
            <span>{{ number_format($invoice->total_without_vat, 2) }} €</span>
        </div>

        <div class="totals-row">
            <strong>PVM:</strong>
            <span>{{ number_format($invoice->vat_amount, 2) }} €</span>
        </div>

        <div class="totals-row">
            <strong>Galutinė suma:</strong>
            <span>{{ number_format($invoice->total_with_vat, 2) }} €</span>
        </div>

    </div>

    <div class="sum-words">

        <strong>Suma žodžiais:</strong>

        {{ ucfirst($amountWords) }} eurai {{ $cents }} ct.

    </div>

    <div class="signature-section">

        <div class="signature-row">
            Sąskaitą išrašė
            <span class="signature-line"></span>
        </div>

        <div class="signature-row">
            Sąskaitą gavo
            <span class="signature-line"></span>
        </div>

    </div>

</div>

</body>
</html>