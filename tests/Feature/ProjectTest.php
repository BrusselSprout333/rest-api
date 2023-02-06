<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Link;
use Hash;

class ProjectTest extends TestCase
{/*
    public function test_register_new_user()
    {
        $user = User::factory()->make();

        $response = $this->postJson('/api/register', [
            'name' => $user['name'],
            'email' => $user['email'],
            'password' => $user['password'],
            'password_confirmation' => $user['password'], 
            'phone' => $user['phone']]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => true,
            ]);
    }

    public function test_login_existing_user()
    {
        $user = User::factory()->create([
            'password' => Hash::make($password = 'verySecretPassword'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user['email'], 
            'password' => $password]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => true,
            ]);
    }
*/
    public function test_see_all_links()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)->getJson('/api/links');    

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => true,
            ]);
    }
/*
    public function test_create_a_new_link()
    {
        $user = User::factory()->create();
        $link = Link::factory()->make([
            'userId' => $user['id'],
        ]);

        $response = $this->actingAs($user)->postJson('/api/links', [
            'originalUrl' => $link['originalUrl'], 
            'isPublic' => $link['isPublic']
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => true,
            ]);
    }

    public function test_delete_a_link()
    {
        $user = User::factory()->create();

        $link = Link::factory()->create([
            'userId' => $user['id'],
        ]);

        $response = $this->actingAs($user)->deleteJson("/api/links/{$link['id']}");

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => true,
            ]);
    }

    public function test_get_original_url_by_shortCode()
    {
        $user = User::factory()->create();

        $link = Link::factory()->create([
            'userId' => $user['id'],
        ]);

        $response = $this->actingAs($user)->getJson("/api/originalUrl/{$link['shortCode']}");    

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => true,
            ]);
    }*/
}
