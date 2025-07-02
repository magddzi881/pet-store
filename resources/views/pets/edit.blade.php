<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8" />
    <title>Edytuj zwierzę</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

    <h1>Edytuj zwierzę</h1>

    @if($errors->any())
        <div class="error-alert">
            <ul style="margin: 0; padding-left: 1.2em;">
                Błędy w formularzu:
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <script>
            setTimeout(() => {
                const alert = document.querySelector('div[style*="position: fixed"]');
                if (alert) alert.style.display = 'none';
            }, 10000);
        </script>
    @endif

    <form method="POST" action="{{ url('/pet/' . $pet->id) }}">
        @csrf
        @method('PUT')

        <div class="form-grid">
            <div>
                <label for="name">Nazwa: <span style="color: red">*</span></label>
                <input type="text" id="name" name="name" class="input" required placeholder="imię zwierzęcia"
                    value="{{ old('name', $pet->name) }}">
            </div>

            <div>
                <label for="status">Status: <span style="color: red">*</span></label>
                <select id="status" name="status" class="input" required>
                    <option value="">( wybierz status )</option>
                    <option value="available" {{ old('status', $pet->status) == 'available' ? 'selected' : '' }}>Dostępny
                    </option>
                    <option value="pending" {{ old('status', $pet->status) == 'pending' ? 'selected' : '' }}>W trakcie
                    </option>
                    <option value="sold" {{ old('status', $pet->status) == 'sold' ? 'selected' : '' }}>Sprzedany</option>
                </select>
            </div>

            <div>
                <label for="photo_url">URL zdjęcia: <span style="color: red">*</span></label>
                <input type="url" id="photo_url" name="photo_url" class="input" required
                    placeholder="adres URL ze zdjęciem" value="{{ old('photo_url', $pet->photoUrls[0] ?? '') }}">
            </div>

            <div>
                <label for="category_name">Kategoria: <span style="color: red">*</span></label>
                <input type="text" id="category_name" name="category_name" class="input" required
                    placeholder="nazwa kategorii" value="{{ old('category_name', $pet->category->name ?? '') }}">
            </div>

            <div>
                <label for="tags">Tagi (oddzielone przecinkami): <span style="color: red">*</span></label>
                <input type="text" id="tags" name="tags" class="input" required placeholder="np. mały, przyjazny"
                    value="{{ old('tags', implode(', ', array_map(fn($tag) => $tag->name, $pet->tags))) }}">
            </div>
        </div>


        <div style="margin-top: 1rem; display: flex; gap: 1rem; align-items: stretch;">
            <button type="submit" class="sbutton">Zapisz zmiany</button>
            <a href="{{ url('/') }}" class="button" style="background-color: #555;">Anuluj</a>

        </div>

    </form>

</body>

</html>