<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\Type;
use App\Models\User;
use App\Models\Article;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiTest extends TestCase
{

    /** @test */
    public function test_article_api()
    {
        $type = Type::create(['type' => 'user']);
        $role = Role::create(['name' => 'user']);
        $user = User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'phone' => '123456789',
            'type_id' => $type->id,
            'role_id' => $role->id,
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->actingAs($user)->getJson('/api/articles');
        $response->assertStatus(200);

        $response = $this->actingAs($user)->postJson('/api/articles',[
                'title' => 'Test Article',
                'body' => 'This is a test article',
                'status' => 'pending',
                'approved_by' => null,
                'user_id' => $user->id,
        ]);
        $response->assertStatus(201);
        $response->assertJson([
            'message' => 'success.store_successful',
        ]);

        $response = $this->actingAs($user)->getJson('/api/articles/1');
        $response->assertStatus(200);
        $response->assertJsonFragment(['title' => 'Test Article']);

        $response = $this->actingAs($user)->json('PATCH', "/api/articles/1",[
            'title' => 'Udate Test Article',
        ]);
        $response->assertStatus(200);
        $response->assertJsonFragment(['title' => 'Udate Test Article']);
        $response->assertJson([
            'message' => 'success.update_successful',
        ]);

        $response = $this->actingAs($user)->json('DELETE', "/api/articles/1");
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'success.delete_successful',
        ]);

    }
}
