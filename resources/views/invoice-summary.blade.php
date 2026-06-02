<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <title>Sąskaitų suvestinė</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<div class="sidebar">
    <div class="sidebar-header">
        PVM SĄSKAITOS-FAKTŪROS
    </div>

    <ul class="menu">
        <li><a href="/create-client">Sukurti klientą</a></li>
        <li><a href="/client-list">Klientų sąrašas</a></li>
        <li><a href="/create-product">Sukurti prekę</a></li>
        <li><a href="/product-list">Prekių sąrašas</a></li>
        <li><a href="/new-invoice">Nauja sąskaita</a></li>
        <li><a href="/invoice-list">Sąskaitų sąrašas</a></li>
        <li><a href="/invoice-summary">Sąskaitų suvestinė</a></li>
    </ul>
</div>

<div class="content-container">

    <h1>Sąskaitų suvestinė</h1>

    <form method="GET" action="/invoice-summary">

        <div class="form-group">
            <label>Data nuo</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}" required>
        </div>

        <div class="form-group">
            <label>Data iki</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}" required>
        </div>

        <button type="submit">
            Filtruoti
        </button>

    </form>

    @if(request('start_date') && request('end_date'))

        <br>

            <a
                href="/invoice-summary-pdf?start_date={{ request('start_date') }}&end_date={{ request('end_date') }}"
                class="generate-btn"
                style="text-decoration:none; display:inline-block; margin-left:10px;"
            >
                Atsisiųsti PDF
            </a>

        <br><br>

        <table>
            <thead>
                <tr>
                    <th>Sąskaitos numeris</th>
                    <th>Klientas</th>
                    <th>Suma be PVM</th>
                    <th>PVM</th>
                    <th>Suma su PVM</th>
                    <th>Data</th>
                </tr>
            </thead>

            <tbody>
                @foreach($invoices as $invoice)

                    @php
                        $client = \App\Models\Client::find($invoice->client_id);
                    @endphp

                    <tr>
                        <td>{{ $invoice->invoice_number }}</td>
                        <td>{{ $client ? $client->company_name : '' }}</td>
                        <td>{{ number_format($invoice->total_without_vat, 2) }} €</td>
                        <td>{{ number_format($invoice->vat_amount, 2) }} €</td>
                        <td>{{ number_format($invoice->total_with_vat, 2) }} €</td>
                        <td>{{ $invoice->created_at->format('Y-m-d') }}</td>
                    </tr>

                @endforeach
            </tbody>
        </table>

    @endif

</div>

</body>
</html>