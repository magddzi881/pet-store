<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\StorePetRequest;
use App\Http\Requests\UpdatePetRequest;

class PetController extends Controller
{
    private string $baseUrl = 'https://petstore.swagger.io/v2';

    /**
     * Display list of pets
     * Supports searching by ID or filtering by status
     */
    public function index(Request $request)
    {
        $searchId = $request->query('search_id');
        $status = $request->query('status');
        $errorMessage = null;
        $pets = [];

        if ($searchId) {
            $response = Http::get($this->baseUrl . '/pet/' . intval($searchId));
            if ($response->successful()) {
                $petData = $response->json();

                if (isset($petData['id'])) {
                    $pets = [Pet::fromArray($petData)];
                } else {
                    $errorMessage = "Nie znaleziono zwierzęcia o ID: $searchId";
                }
            } else {
                $errorMessage = "Nie znaleziono zwierzęcia o ID: $searchId";
            }
        } else if ($status) {
            $response = Http::get($this->baseUrl . '/pet/findByStatus', [
                'status' => $status,
            ]);
            if ($response->successful()) {
                $petsData = $response->json();
                $pets = array_map(fn($petData) => Pet::fromArray($petData), $petsData);
            } else {
                $errorMessage = "Wystąpił błąd podczas pobierania zwierząt o statusie: $status";
            }
        } else {
            $response = Http::get($this->baseUrl . '/pet/findByStatus', [
                'status' => 'available',
            ]);
            if ($response->successful()) {
                $petsData = $response->json();
                $pets = array_map(fn($petData) => Pet::fromArray($petData), $petsData);
            } else {
                $errorMessage = "Wystąpił błąd podczas pobierania zwierząt.";
            }
        }

        return view('pets.index', compact('pets', 'errorMessage'));
    }


    public function create()
    {
        return view('pets.create');
    }

    /**
     * Store a newly created pet in the external API
     */
    public function save(StorePetRequest $request)
    {
        $validated = $request->validated();

        $payload = [
            'id' => rand(1000, 9999),
            'name' => $validated['name'],
            'status' => $validated['status'],
            'photoUrls' => [$validated['photo_url']],
            'category' => [
                'id' => rand(1000, 9999),
                'name' => $validated['category_name']
            ],
            'tags' => collect(explode(',', $validated['tags']))
                ->map(fn($tag) => [
                    'id' => rand(1000, 9999),
                    'name' => trim($tag)
                ])
                ->toArray(),
        ];

        $response = Http::post($this->baseUrl . '/pet', $payload);

        if (!$response->successful()) {
            return back()->withErrors(['api_error' => 'Nie udało się dodać zwierzęcia']);
        }

        return redirect('/')->with('success', 'Zwierzę zostało dodane');
    }

    public function edit($id)
    {
        $response = Http::get($this->baseUrl . '/pet/' . $id);

        if (!$response->successful() || empty($response->json('id'))) {
            return redirect('/')
                ->with('errorMessage', 'Nie znaleziono zwierzęcia o ID: ' . $id);
        }

        $pet = Pet::fromArray($response->json());
        return view('pets.edit', compact('pet'));
    }

    /**
     * Update the specified pet in the external API
     */
    public function update(UpdatePetRequest $request, $id)
    {
        $validated = $request->validated();

        $payload = [
            'id' => (int) $id,
            'name' => $validated['name'],
            'status' => $validated['status'],
            'photoUrls' => [$validated['photo_url']],
            'category' => [
                'id' => rand(1000, 9999),
                'name' => $validated['category_name']
            ],
            'tags' => collect(explode(',', $validated['tags']))
                ->map(fn($tag) => [
                    'id' => rand(1000, 9999),
                    'name' => trim($tag)
                ])
                ->toArray(),
        ];

        $response = Http::put($this->baseUrl . '/pet', $payload);

        if (!$response->successful()) {
            return back()->withErrors(['api_error' => 'Nie udało się zaktualizować zwierzęcia']);
        }

        return redirect('/')->with('success', 'Zwierzę zostało zaktualizowane');
    }

    /**
     * Remove the specified pet from the external API
     */
    public function delete($id)
    {
        $response = Http::delete($this->baseUrl . '/pet/' . $id);

        if (!$response->successful()) {
            return back()->withErrors(['api_error' => 'Nie udało się usunąć zwierzęcia']);
        }

        return redirect('/')->with('success', 'Zwierzę zostało usunięte');
    }


}


