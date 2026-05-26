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

    *{
        margin:0;
        padding:0;
        box-sizing:border-box;
    }

    html,
    body{
        width:100%;
        background:white;
        font-family: DejaVu Sans, sans-serif;
        color:#000;
    }

    body{
        padding:0;
    }

    /* A4 sheet */

    .invoice-sheet{

        width:190mm;

        margin:0 auto;

        background:white;

        padding:15mm;

        box-sizing:border-box;
    }

    /* Header */

    .invoice-title{
        text-align:center;
        font-size:22px;
        font-weight:bold;
        margin-bottom:8px;
    }

    .invoice-number{
        text-align:center;
        font-size:16px;
        font-weight:bold;
        margin-bottom:8px;
    }

    .invoice-date{
        text-align:center;
        font-size:13px;
        margin-bottom:40px;
    }

    /* Seller / Buyer */

    .invoice-parties{
        display:table;
        width:100%;
        table-layout:fixed;
        margin-bottom:35px;
    }

    .party-block{
        display:table-cell;
        width:50%;
        vertical-align:top;
    }

    .seller-block{
        padding-right:30px;
    }

    .buyer-block{
        padding-left:30px;
    }

    .party-title{
        font-size:12px;
        font-weight:bold;
        margin-bottom:10px;
        padding-bottom:4px;
        border-bottom:1px solid #999;
    }

    .party-row{
        font-size:10px;
        margin-bottom:5px;
        line-height:1.4;
    }

    /* Table */

    table{
        width:100%;
        border-collapse:collapse;
        table-layout:fixed;
        margin-top:20px;
    }

    th,
    td{
        border:1px solid #000;
        padding:6px;
        font-size:12px;
        text-align:center;
        overflow:hidden;
        word-wrap:break-word;
    }

    th{
        background:#f2f2f2;
        font-weight:bold;
    }

    /* Column widths */

    th:nth-child(1),
    td:nth-child(1){
        width:8%;
    }

    th:nth-child(2),
    td:nth-child(2){
        width:40%;
    }

    th:nth-child(3),
    td:nth-child(3){
        width:16%;
    }

    th:nth-child(4),
    td:nth-child(4){
        width:18%;
    }

    th:nth-child(5),
    td:nth-child(5){
        width:18%;
    }

    /* Totals */

    .totals{
        width:260px;
        margin-left:auto;
        margin-top:25px;
        margin-right:0;
    }

    .totals-row{
        overflow:hidden;
        margin-bottom:8px;
        font-size:13px;
    }

    .totals-label{
        float:left;
        font-weight:bold;
    }

    .totals-value{
        float:right;
    }

    /* Sum words */

    .sum-words{
        margin-top:40px;
        font-size:13px;
        line-height:1.6;
    }

    /* Signatures */

    .signature-section{
        display:table;
        width:100%;
        table-layout:fixed;
        margin-top:80px;
    }

    .signature-row{
        display:table-cell;
        width:50%;
        vertical-align:top;
        font-size:13px;
    }

    .signature-line{
        display:inline-block;
        width:220px;
        border-bottom:1px solid #000;
        margin-left:12px;
    }

    /* Print */

    @page{
        size:A4;
        margin:10mm;
    }

    @media print{

        html,
        body{
            background:white;
        }

        .invoice-sheet{
            width:100%;
            margin:0;
            padding:0;
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