<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_list_clients(): void
    {
        $user = User::factory()->create();
        Client::factory()->count(2)->create();

        $response = $this->actingAs($user)->get('/clients');

        $response->assertStatus(200);
        $response->assertSee('Clientes');
    }
}
