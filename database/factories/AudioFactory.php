<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Audio;

class AudioFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Audio::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'url' => $this->faker->url(),
        ];
    }
}
