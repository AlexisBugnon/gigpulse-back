<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Creation of 10 users, who will each have 3 gigs (uses the hasGigs relationship defined in the factory)
        User::factory()->count(10)->hasGigs(3)->create();

        // Creation of 5 users, who will each have 3 favorite gigs (uses the hasFavoriteGigs relationship defined in the factory)
        User::factory()->count(5)->hasFavoriteGigs(3)->create();
    }
}
