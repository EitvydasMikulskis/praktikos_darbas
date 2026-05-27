<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klientų sąrašas</title>

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
    </ul>
</div>

<div class="content-container">

    <h1>Nauja sąskaita</h1>

        @if(session('error'))

            <div class="error">
                {{ session('error') }}
            </div>

        @endif
        @if(session()->has('success'))

            <div class="success">
                {{ session()->get('success') }}
            </div>

        @endif

    <form method="POST" action="/new-invoice">

        @csrf

        <div class="form-group">

            <h2 class="section-title">
                Pasirinkite klientą
            </h2>

            <select name="client_id" class="invoice-select" required>

                <option value="">
                    -- Pasirinkti klientą --
                </option>

                @foreach($clients as $client)

                    <option value="{{ $client->id }}">
                        {{ $client->company_name }}
                    </option>

                @endforeach

            </select>

        </div>

        <h2 class="section-title">
            Prekių pasirinkimas
        </h2>

        <div class="products-scroll-container">

    <div class="invoice-products">

            @foreach($products as $product)

            <div class="product-card">

                <div class="product-left">

                    <label class="product-checkbox">

                        <input
                            type="checkbox"
                            name="products[]"
                            value="{{ $product->id }}"
                        >

                        <div>

                            <div class="product-name">
                                {{ $product->product_name }}
                            </div>

                            <div class="product-price">
                                {{ $product->unit_price }} €
                            </div>

                        </div>

                    </label>

                </div>

                <div class="product-right">

                    <input
                        type="number"
                        name="quantities[{{ $product->id }}]"
                        class="quantity-input"
                        min="1"
                        value="1"
                    >

                </div>

            </div>

            @endforeach

        </div>

    </div>

        <button type="submit" class="generate-btn">
            Generuoti sąskaitą
        </button>

    </form>

</div>

</body>
</html>