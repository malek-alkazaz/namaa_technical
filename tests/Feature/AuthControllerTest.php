<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\Type;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;


    /** @test */
    public function test_register_endpoint()
    {
        $type = Type::create(['type' => 'user']);
        $role = Role::create(['name' => 'user']);

        $response = $this->postJson('/api/register', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'phone' => '777456789',
            'type_id' => $type->id,
            'role_id' => $role->id,
            'email' => 'john11@example.com',
            'password' => bcrypt('password'),
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'message' => 'registered_successful',
        ]);
    }

    /** @test */
    public function test_login_endpoint()
    {
        $type = Type::create(['type' => 'user']);
        $role = Role::create(['name' => 'user']);

        $user = User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'phone' => '666456789',
            'type_id' => $type->id,
            'role_id' => $role->id,
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'phone' => $user->phone,
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'login_successful',
        ]);
    }

    /** @test */
    public function test_login_with_invalid_credentials()
    {

        $type = Type::create(['type' => 'user']);
        $role = Role::create(['name' => 'user']);

        $user = User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'phone' => '666456789',
            'type_id' => $type->id,
            'role_id' => $role->id,
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'phone' => $user->phone,
            'password' => 'pass',
        ]);

        $response->assertStatus(403);
        $response->assertJson([
            'message' => 'Invalid_credentials',
            'status_code' => 403,
        ]);
    }


    /** @test */
    public function test_logout_endpoint()
    {
        $type = Type::create(['type' => 'user']);
        $role = Role::create(['name' => 'user']);

        $user = User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'phone' => '666456789',
            'type_id' => $type->id,
            'role_id' => $role->id,
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
        ]);

        $token = $user->createToken('secret')->plainTextToken;

        $response = $this->postJson('/api/logout', [], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Logout_successfully',
            ]);
    }
}
