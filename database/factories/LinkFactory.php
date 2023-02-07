<?php

namespace Database\Factories;

use App\Helpers\Utilites\ShortLinkGenerator;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Link>
 */
class LinkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $url = fake()->unique()->url();
        $user = User::factory()->create();

        return [
            'userId' => $user->id,
            'originalUrl' => $url,
            'shortCode' => app(ShortLinkGenerator::class)->generateShortLink($url, $user->id),
            'isPublic' => fake()->boolean(),
            'createdDate' => fake()->date(),
        ];
    }
}
