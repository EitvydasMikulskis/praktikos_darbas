<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prekių sąrašas</title>

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

    <h1>Prekių sąrašas</h1>

    @if(session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    <table>

        <thead>

            <tr>
                <th>ID</th>
                <th>Prekės pavadinimas</th>
                <th>Mato vienetas</th>
                <th>Vieneto kaina, Eur</th>
                <th>Veiksmai</th>
            </tr>

        </thead>

        <tbody>

        @foreach($products as $product)

        <tr>

            <form method="POST" action="/product-update/{{ $product->id }}">

                @csrf
                @method('PUT')

                <td>{{ $product->id }}</td>

                <td>
                    <input
                        type="text"
                        name="product_name"
                        value="{{ $product->product_name }}"
                        class="edit-input"
                        readonly
                    >
                </td>

                <td>
                    <input
                        type="text"
                        name="measurement_unit"
                        value="{{ $product->measurement_unit }}"
                        class="edit-input"
                        readonly
                    >
                </td>

                <td>
                    <input
                        type="number"
                        step="0.01"
                        name="unit_price"
                        value="{{ $product->unit_price }}"
                        class="edit-input"
                        readonly
                    >
                </td>

                <td class="action-buttons">

                    <button
                        type="button"
                        class="edit-btn"
                        onclick="enableEdit(this)"
                    >
                        Redaguoti
                    </button>

                    <button
                        type="submit"
                        class="save-floating-btn"
                    >
                        Išsaugoti
                    </button>

                    <button
                        type="button"
                        class="cancel-floating-btn"
                        onclick="cancelEdit(this)"
                    >
                        Atšaukti
                    </button>

            </form>

                    <form method="POST" action="/product-delete/{{ $product->id }}">

                        @csrf
                        @method('DELETE')

                        <button type="submit" class="delete-btn">
                            Ištrinti
                        </button>

                    </form>

                </td>

        </tr>

        @endforeach

        </tbody>

    </table>

</div>

<script>

function enableEdit(button)
{
    let row = button.closest('tr');

    let inputs = row.querySelectorAll('.edit-input');

    inputs.forEach(input => {

        input.dataset.original = input.value;

        input.removeAttribute('readonly');

        input.style.background = '#fff';

        input.style.border = '2px solid #3498db';

    });

    let saveButton = row.querySelector('.save-floating-btn');

    let cancelButton = row.querySelector('.cancel-floating-btn');

    saveButton.classList.add('active');

    cancelButton.classList.add('active');
}

function cancelEdit(button)
{
    let row = button.closest('tr');

    let inputs = row.querySelectorAll('.edit-input');

    inputs.forEach(input => {

        input.value = input.dataset.original;

        input.setAttribute('readonly', true);

        input.style.background = 'transparent';

        input.style.border = 'none';

    });

    let saveButton = row.querySelector('.save-floating-btn');

    let cancelButton = row.querySelector('.cancel-floating-btn');

    saveButton.classList.remove('active');

    cancelButton.classList.remove('active');
}

</script>

</body>
</html>