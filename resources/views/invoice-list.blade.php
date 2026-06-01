<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sąskaitų sąrašas</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<script>
    function toggleMailForm(invoiceId)
    {
        let row =
            document.getElementById(
                'mail-row-' + invoiceId
            );

        if(row.style.display === 'none')
        {
            row.style.display = 'table-row';
        }
        else
        {
            row.style.display = 'none';
        }
    }
</script>

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
    </ul>

</div>

<div class="content-container">

    <h1>Sąskaitų sąrašas</h1>

    <table>

        <thead>

            <tr>
                <th>ID</th>
                <th>Sąskaitos numeris</th>
                <th>Klientas</th>
                <th>Suma su PVM</th>
                <th>Data</th>
                <th class="invoice-actions-column">
                    Veiksmai
                </th>
            </tr>

        </thead>

        <tbody>

            @foreach($invoices as $invoice)

            @php
                $client = \App\Models\Client::find($invoice->client_id);
            @endphp

            <tr>

                <td>{{ $invoice->id }}</td>

                <td>{{ $invoice->invoice_number }}</td>

                <td>{{ $client->company_name }}</td>

                <td>{{ number_format($invoice->total_with_vat, 2) }} €</td>

                <td>{{ $invoice->created_at->format('Y-m-d') }}</td>

                <td class="invoice-actions-column">

                    <div class="invoice-action-icons">

                        <a href="/invoice/{{ $invoice->id }}">

                            <img
                                src="{{ asset('images/view.png') }}"
                                alt="view"
                                class="invoice-icon"
                            >

                        </a>

                        <a href="/invoice-pdf/{{ $invoice->id }}">

                            <img
                                src="{{ asset('images/pdf.png') }}"
                                alt="pdf"
                                class="invoice-icon"
                            >

                        </a>

                        <a href="javascript:void(0)"
                        onclick="toggleMailForm({{ $invoice->id }})">

                            <img
                                src="{{ asset('images/mail.png') }}"
                                alt="mail"
                                class="invoice-icon"
                            >

                        </a>

                    </div>

                </td>

            </tr>
            <tr
                id="mail-row-{{ $invoice->id }}"
                class="mail-row"
                style="display:none;"
            >

                <td colspan="6">

                    <form
                        method="POST"
                        action="/invoice-send/{{ $invoice->id }}"
                        class="mail-form"
                    >

                        @csrf

                        <div class="mail-field">

                            <label>El. pašto adresas</label>

                            <input
                                type="email"
                                name="email"
                                class="mail-input"
                                required
                            >

                        </div>

                        <div class="mail-field">

                            <label>Papildoma informacija</label>

                            <textarea
                                name="message"
                                class="mail-textarea"
                            ></textarea>

                        </div>

                        <button type="submit" class="generate-btn">
                            Siųsti
                        </button>

                    </form>

                </td>

            </tr>

            @endforeach

        </tbody>

    </table>

</div>

</body>
</html>