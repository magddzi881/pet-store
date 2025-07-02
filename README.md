# Aplikacja do zarządzania zwierzętami Laravel + REST API

Aplikacja webowa oparta na Laravelu, która integruje się z publicznym REST API: [Swagger Petstore](https://petstore.swagger.io/) w celu zarządzania zasobem `/pet`. Umożliwia dodawanie, edycję, usuwanie oraz przeglądanie zwierząt poprzez interfejs z formularzami.

## Uruchamianie aplikacji

### Klonowanie repozytorium

```bash
git clone https://github.com/magddzi881/pet-store
```

### Instalacja zależności

```bash
composer install
```

### Uruchomienie serwera developerskiego

```bash
php artisan serve
```

Aplikacja będzie dostępna pod adresem: [`http://localhost:8000`](http://localhost:8000)

## Funkcjonalności

-   Pobieranie listy zwierząt według statusu (`available`, `pending`, `sold`),
-   Wyszukiwanie zwierzęcia po ID,
-   Wyśweitlanie zdjęć zwierząt, w przypadku ich braku zastępowanie ich placeholderem,
-   Dodawanie nowego zwierzęcia przez formularz,
-   Edytowanie danych zwierzęcia,
-   Usuwanie zwierzęcia,
-   Informacje o błędach widoczne dla użytkownika w formie podobnej do pop-up,
-   Obsługa błędów API (np. brak połączenia, brak zwierzęcia, błędne dane),
-   Przejrzysty interfejs (widoki: `index`, `create`, `edit`),
-   Konteneryzacja (Docker)
-   Testy funkcjonalne (mockowane odpowiedzi HTTP).

## Uruchamianie aplikacji w Dockerze

W projekcie dostępna jest konfiguracja Docker umożliwiająca uruchomienie aplikacji Laravel w kontenerze PHP.

### Pliki konfiguracyjne:

-   `Dockerfile` – obraz PHP 8.4 CLI z Composerem i rozszerzeniami,
-   `docker-compose.yml` – usługa laravel uruchamiającą aplikację i mapującą port 8000.

### Budowanie i uruchomienie kontenera

```bash
docker-compose up --build
```

Po zbudowaniu i uruchomieniu serwera aplikacja również będzie dostępna pod adresem [`http://localhost:8000`](http://localhost:8000)

## Testy

Testy jednostkowe i funkcjonalne znajdują się w katalogu `tests/Feature/PetControllerTest.php`

### Uruchomienie testów:

```bash
php artisan test --filter=PetControllerTest
```

### Zakres testowanych endpointów

| Endpoint         | Metoda   | Opis                                     |
| ---------------- | -------- | ---------------------------------------- |
| `/`              | `GET`    | Lista zwierząt lub zwierzę po ID         |
| `/pet`           | `POST`   | Dodawanie nowego zwierzęcia              |
| `/pet/{id}/edit` | `GET`    | Formularz edycji istniejącego zwierzęcia |
| `/pet/{id}`      | `PUT`    | Aktualizacja danych zwierzęcia           |
| `/pet/{id}`      | `DELETE` | Usunięcie zwierzęcia                     |
