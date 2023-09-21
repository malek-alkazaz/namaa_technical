<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\Type;
use App\Models\User;
use App\Models\Article;
use App\Enums\ArticleStatus;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArticleTest extends TestCase
{

    /** @test */
    public function test_article_creation()
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

        $data = [
            'title' => 'Test Article',
            'body' => 'This is a test article',
        ];

        $article = Article::create([
            'title' => 'Test Article',
            'body' => 'This is a test article',
            'status' => ArticleStatus::Pending,
            'approved_by' => null,
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'title' => $data['title'],
            'body' => $data['body'],
            'status' => ArticleStatus::Pending,
            'approved_by' => null,
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function test_article_approved()
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

        $type = Type::create(['type' => 'admin']);
        $role = Role::create(['name' => 'admin']);
        $admin = User::create([
            'first_name' => 'Admin',
            'last_name' => 'Doe',
            'phone' => '999999999',
            'type_id' => $type->id,
            'role_id' => $role->id,
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        $article = Article::factory()->create([
            'body' => 'This is a test article',
            'title' => 'Test Article',
            'status' => 'pending',
            'approved_by' => null,
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($admin)
        ->json('POST', "/api/articles/{$article->id}/approve", [
            'status' => 'accepted',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'status' => 'accepted',
            'approved_by' => $admin->id,
        ]);
        $this->assertDatabaseCount('articles', 1);
        $this->assertDatabaseCount('users', 2);
    }

    /** @test */
    public function test_article_review()
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


        $type = Type::create(['type' => 'admin']);
        $role = Role::create(['name' => 'admin']);
        $admin = User::create([
            'first_name' => 'Admin',
            'last_name' => 'Doe',
            'phone' => '999999999',
            'type_id' => $type->id,
            'role_id' => $role->id,
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        $pendingArticle = Article::factory()->create([
            'body' => 'This is a test article',
            'title' => 'Test Article',
            'status' => 'pending',
            'approved_by' => null,
            'user_id' => $user->id,
        ]);

        $approvedArticle = Article::factory()->create([
            'body' => 'This is a test article',
            'title' => 'Test Article',
            'status' => 'accepted',
            'approved_by' => $admin->id,
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($admin)->get('/api/articles/review');

        $response->assertStatus(200);
        $this->assertDatabaseCount('articles', 2);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment([
            'id' => $pendingArticle->id,
            'status' => 'pending',
        ]);
    }

}
