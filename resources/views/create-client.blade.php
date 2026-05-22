<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sukurti klientą</title>
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
        <li><a href="/pdf-export">PDF eksportas</a></li>
        <li><a href="/invoice-send">Siųsti el. paštu</a></li>
    </ul>

</div>

<div class="content-container">

    <h1>Sukurti klientą</h1>

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

    <form method="POST" action="/create-client">

        @csrf

        <div class="form-group">
            <label>Pirkėjas *</label>
            <input type="text" name="company_name" required>
        </div>

        <div class="form-group">
            <label>Įmonės kodas</label>
            <input type="text" name="company_code">
        </div>

        <div class="form-group">
            <label>Adresas *</label>
            <input type="text" name="address" required>
        </div>

        <div class="form-group">
            <label>PVM kodas</label>
            <input type="text" name="vat_code">
        </div>

        <div class="form-group">
            <label>Telefono numeris *</label>
            <input type="text" name="phone" required>
        </div>

        <button type="submit">
            Išsaugoti klientą
        </button>

    </form>

</div>

</body>
</html>