<?php

namespace Database\Factories;

use App\Models\Gig;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gigId = Gig::pluck('id')->random();
        $userId = User::pluck('id')->random();
        return [
            'gig_id' => $gigId,
            'user_id' => $userId,
            'rating' => fake()->numberBetween(1, 5),
            'comment' => fake()->text(),
            'created_at' => now(),
        ];
    }
}
