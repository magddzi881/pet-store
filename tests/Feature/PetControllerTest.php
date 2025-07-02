<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class PetControllerTest extends TestCase
{
    public function test_index_returns_pets_by_default_status()
    {
        Http::fake([
            'https://petstore.swagger.io/v2/pet/findByStatus*' => Http::response([
                [
                    'id' => 1,
                    'name' => 'Burek',
                    'photoUrls' => ['http://example.com/burek.jpg'],
                    'category' => ['id' => 10, 'name' => 'Pies'],
                    'tags' => [],
                    'status' => 'available',
                ],
            ], 200),
        ]);

        $response = $this->get('/?status=');

        $response->assertStatus(200);
        $response->assertViewHas('pets');
        $pets = $response->viewData('pets');
        $this->assertCount(1, $pets);
        $this->assertEquals('Burek', $pets[0]->name);
    }

    public function test_index_returns_pet_by_search_id()
    {
        $petData = [
            'id' => 42,
            'name' => 'Rex',
            'photoUrls' => ['http://example.com/rex.jpg'],
            'category' => ['id' => 20, 'name' => 'Kot'],
            'tags' => [],
            'status' => 'sold',
        ];

        Http::fake([
            'https://petstore.swagger.io/v2/pet/42' => Http::response($petData, 200),
        ]);

        $response = $this->get('/?search_id=42');

        $response->assertStatus(200);
        $response->assertViewHas('pets');
        $pets = $response->viewData('pets');
        $this->assertCount(1, $pets);
        $this->assertEquals('Rex', $pets[0]->name);
    }

    public function test_index_returns_error_when_pet_not_found_by_id()
    {
        Http::fake([
            'https://petstore.swagger.io/v2/pet/999' => Http::response([], 404),
        ]);

        $response = $this->get('/?search_id=999');

        $response->assertStatus(200);
        $response->assertViewHas('errorMessage');
        $this->assertStringContainsString('Nie znaleziono zwierzęcia o ID', $response->viewData('errorMessage'));
    }

    public function test_save_redirects_back_on_api_error()
    {
        Http::fake([
            'https://petstore.swagger.io/v2/pet' => Http::response([], 500),
        ]);

        $response = $this->post('/pet', [
            'name' => 'Test',
            'status' => 'available',
            'photo_url' => 'http://example.com/photo.jpg',
            'category_name' => 'TestCategory',
            'tags' => 'tag1, tag2',
        ]);

        $response->assertSessionHasErrors('api_error');
        $response->assertRedirect();
    }

    public function test_save_redirects_with_success()
    {
        Http::fake([
            'https://petstore.swagger.io/v2/pet' => Http::response([], 200),
        ]);

        $response = $this->post('/pet', [
            'name' => 'Test',
            'status' => 'available',
            'photo_url' => 'http://example.com/photo.jpg',
            'category_name' => 'TestCategory',
            'tags' => 'tag1, tag2',
        ]);

        $response->assertRedirect('/');
        $response->assertSessionHas('success', 'Zwierzę zostało dodane');
    }

    public function test_edit_returns_pet_for_existing_id()
    {
        $petData = [
            'id' => 5,
            'name' => 'Mimi',
            'photoUrls' => ['http://example.com/mimi.jpg'],
            'category' => ['id' => 30, 'name' => 'Ptak'],
            'tags' => [],
            'status' => 'pending',
        ];

        Http::fake([
            'https://petstore.swagger.io/v2/pet/5' => Http::response($petData, 200),
        ]);

        $response = $this->get('/pet/5/edit');

        $response->assertStatus(200);
        $response->assertViewHas('pet');
        $this->assertEquals('Mimi', $response->viewData('pet')->name);
    }

    public function test_edit_aborts_404_for_nonexistent_pet()
    {
        Http::fake([
            'https://petstore.swagger.io/v2/pet/9999' => Http::response([], 404),
        ]);

        $response = $this->get('/pet/9999/edit');

        $response->assertStatus(404);
    }

    public function test_update_redirects_back_on_api_error()
    {
        Http::fake([
            'https://petstore.swagger.io/v2/pet' => Http::response([], 500),
        ]);

        $response = $this->put('/pet/1234', [
            'name' => 'UpdatedName',
            'status' => 'sold',
            'photo_url' => 'http://example.com/updated.jpg',
            'category_name' => 'UpdatedCategory',
            'tags' => 'updated,tag',
        ]);

        $response->assertSessionHasErrors('api_error');
        $response->assertRedirect();
    }

    public function test_update_redirects_with_success()
    {
        Http::fake([
            'https://petstore.swagger.io/v2/pet' => Http::response([], 200),
        ]);

        $response = $this->put('/pet/1234', [
            'name' => 'UpdatedName',
            'status' => 'sold',
            'photo_url' => 'http://example.com/updated.jpg',
            'category_name' => 'UpdatedCategory',
            'tags' => 'updated,tag',
        ]);

        $response->assertRedirect('/');
        $response->assertSessionHas('success', 'Zwierzę zostało zaktualizowane');
    }

    public function test_delete_redirects_back_on_api_error()
    {
        Http::fake([
            'https://petstore.swagger.io/v2/pet/1234' => Http::response([], 500),
        ]);

        $response = $this->delete('/pet/1234');

        $response->assertSessionHasErrors('api_error');
        $response->assertRedirect();
    }

    public function test_delete_redirects_with_success()
    {
        Http::fake([
            'https://petstore.swagger.io/v2/pet/1234' => Http::response([], 200),
        ]);

        $response = $this->delete('/pet/1234');

        $response->assertRedirect('/');
        $response->assertSessionHas('success', 'Zwierzę zostało usunięte');
    }
}

