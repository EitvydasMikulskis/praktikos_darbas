<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klientų sąrašas</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

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

    <h1>Klientų sąrašas</h1>

    @if(session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    <table>

        <thead>
            <tr>
                <th>ID</th>
                <th>Įmonės pavadinimas</th>
                <th>Įmonės kodas</th>
                <th>Adresas</th>
                <th>PVM kodas</th>
                <th>Telefonas</th>
                <th>Veiksmai</th>
            </tr>
        </thead>

        <tbody>

            @foreach($clients as $client)

            <tr>

            <form method="POST" action="/client-update/{{ $client->id }}">

                @csrf
                @method('PUT')

                <td>{{ $client->id }}</td>

                <td>
                    <input 
                        type="text"
                        name="company_name"
                        value="{{ $client->company_name }}"
                        class="edit-input"
                        readonly
                    >
                </td>

                <td>
                    <input 
                        type="text"
                        name="company_code"
                        value="{{ $client->company_code }}"
                        class="edit-input"
                        readonly
                    >
                </td>

                <td>
                    <input 
                        type="text"
                        name="address"
                        value="{{ $client->address }}"
                        class="edit-input"
                        readonly
                    >
                </td>

                <td>
                    <input 
                        type="text"
                        name="vat_code"
                        value="{{ $client->vat_code }}"
                        class="edit-input"
                        readonly
                    >
                </td>

                <td>
                    <input 
                        type="text"
                        name="phone"
                        value="{{ $client->phone }}"
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

                    <form method="POST" action="/client-delete/{{ $client->id }}">

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

</body>
</html>