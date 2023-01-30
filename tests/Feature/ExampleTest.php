<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Link;

class ExampleTest extends TestCase
{
    public function test_login()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'niki@ii.com', 
            'password' => 'niki123123']);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => true,
            ]);
    }

    public function test_see_all_links()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson('/api/links');    

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => true,
            ]);
    }

    public function test_create_a_link()
    {
        $user = User::factory()->create();

        $link = Link::factory()->make();

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
}
