<!DOCTYPE html>
<html lang="lt">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Sukurti prekę</title>

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

    <h1>Sukurti prekę</h1>

    @if(session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())

        <div class="error">

            @foreach($errors->all() as $error)

                <div>{{ $error }}</div>

            @endforeach

        </div>

    @endif

    <form method="POST" action="/create-product">

        @csrf

        <div class="form-group">

            <label>Prekės pavadinimas *</label>

            <input 
                type="text"
                name="product_name"
                required
            >

        </div>

        <div class="form-group">

            <label>Mato vienetas *</label>

            <input 
                type="text"
                name="measurement_unit"
                required
            >

        </div>

        <div class="form-group">

            <label>Vieneto kaina *</label>

            <input 
                type="number"
                step="0.01"
                name="unit_price"
                required
            >

        </div>

        <button type="submit">
            Išsaugoti prekę
        </button>

    </form>

</div>

</body>
</html>