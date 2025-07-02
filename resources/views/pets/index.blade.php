<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8" />
    <title>Pet Store</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
    @if(!empty($errorMessage))
        <div id="error-alert" class="error-alert">
            {{ $errorMessage }}
        </div>

        <script>
            setTimeout(() => {
                const alert = document.getElementById('error-alert');
                if (alert) alert.style.display = 'none';
            }, 10000);
        </script>
    @endif

    @if(session('success'))
        <div class="success-alert">
            {{ session('success') }}
        </div>

        <script>
            setTimeout(() => {
                const alert = document.querySelector('div[style*="position: fixed"]');
                if (alert) alert.style.display = 'none';
            }, 3000);
        </script>
    @endif

    <h1>Lista zwierząt</h1>

    <div class="top-actions"
        style="display: flex; justify-content: space-between; align-items: baseline; gap: 1rem; flex-wrap: wrap;">
        <a href="{{ url('/pet/create') }}" class="button">Dodaj zwierzę</a>
        <div style="display: flex; justify-content: space-between; align-items: baseline; gap: 1rem; flex-wrap: wrap;">
            <form method="GET" action="{{ url('/') }}"
                style="margin: 0; display: flex; align-items: baseline; gap: 0.5rem;">
                <label for="status" style="white-space: nowrap;">Filtruj po statusie:</label>
                <select name="status" onchange="this.form.submit()" class="input"
                    style="width: 140px; cursor: pointer;">
                    <option value="">wybierz status</option>
                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Dostępny</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>W trakcie</option>
                    <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>Sprzedany</option>
                </select>
            </form>

            <form method="GET" action="{{ url('/') }}"
                style="margin: 0; display: flex; align-items: baseline; gap: 0.5rem;">
                <label for="search_id" style="white-space: nowrap;">Szukaj po ID:</label>
                <input type="number" id="search_id" name="search_id" class="input" style="width: 120px;"
                    value="{{ request('search_id') }}" placeholder="np. 1234"
                    onkeydown="if(event.key === 'Enter') this.form.submit();" />
                <button type="submit" class="sbutton">Szukaj</button>
            </form>
        </div>
    </div>

    @if(isset($pets) && count($pets) > 0)
        <table>
            <thead>
                <tr>
                    <th>Zdjęcie</th>
                    <th>ID</th>
                    <th>Nazwa</th>
                    <th>Kategoria</th>
                    <th>Tagi</th>
                    <th>Status</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pets as $pet)
                    <tr>
                        <td>
                            @php
                                $imageUrl = (!empty($pet->photoUrls) && count($pet->photoUrls) > 0)
                                    ? $pet->photoUrls[0]
                                    : asset('images/placeholder.png');
                            @endphp

                            <img src="{{ $imageUrl }}" alt="Zdjęcie {{ $pet->name }}"
                                style="max-width:100px; height:auto; border-radius:6px;">
                        </td>

                        <td>{{ $pet->id ?? '-' }}</td>
                        <td>{{ $pet->name ?? '-' }}</td>
                        <td>{{ $pet->category->name ?? '-' }}</td>
                        <td>
                            @if(!empty($pet->tags) && count($pet->tags) > 0)
                                {{ implode(', ', array_map(fn($tag) => $tag->name, $pet->tags)) }}
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $pet->status ?? '-' }}</td>
                        <td>
                            <div class="actions">
                                <form method="GET" action="{{ url('/pet/' . $pet->id . '/edit') }}">
                                    <button type="submit" class="btn btn-edit">Edytuj</button>
                                </form>

                                <form method="POST" action="{{ url('/pet/' . $pet->id) }}"
                                    onsubmit="return confirm('Na pewno usunąć?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-delete">Usuń</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Brak dostępnych zwierząt lub błąd wczytywania.</p>
    @endif

</body>

</html>