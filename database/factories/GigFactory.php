<?php

namespace Database\Factories;

use App\Models\Gig;
use App\Models\User;
use App\Models\Category;
use App\Models\Review;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Gig>
 */
class GigFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Get a list of the available IDs from the users and categories tables
        // random() is used to select a random ID from these lists
        $userId = User::pluck('id')->random();
        // Retrieve an array of the values ​​from the 'id' column of the 'categories' table, then use the method random()
        $categoryId = Category::pluck('id')->random();

        // Generate a slug based on the title
        $title = fake()->sentence();
        return [
            'user_id' => $userId,
            'category_id' => $categoryId,
            'title' => $title,
            // picture: lien qui genere des photos aléatoires, avec la taille renseignée
            'picture' => 'https://picsum.photos/600/400?random=' . mt_rand(),
            'description' => fake()->text(),
            'price' => fake()->randomFloat(2, 5, 150),
            'average_rating' => 0,
            'is_active' => fake()->boolean(),
            'slug' => Str::slug($title), // Generate a slug based on the title
            'created_at' => now(),
        ];
    }

    // Additional task after the creation of the model
    public function configure()
    {
        return $this->afterCreating(function (Gig $gig) {
            // Create a certain number of reviews for this gig
            $reviews = Review::factory()->count(5)->create([
                'gig_id' => $gig->id
            ]);

            // Calculate the average rating
            $averageRating = $reviews->avg('rating');

            // Update the gig with the average rating
            $gig->update(['average_rating' => $averageRating]);
        });
    }
}
