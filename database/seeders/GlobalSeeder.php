<?php

namespace Database\Seeders;

use App\Models\Gig;
use App\Models\Tag;
use App\Models\User;
use App\Models\Review;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class GlobalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'John',
            'email' => 'john.doe@gmail.com',
            'password' => Hash::make('password'),
            'profile_picture' => 'https://api.dicebear.com/7.x/adventurer/svg?seed=John',
            'role' => 'Super admin',
            'is_active' => 1,
        ]);
        DB::table('users')->insert([
            'name' => 'Jane',
            'email' => 'jane.doe@gmail.com',
            'password' => Hash::make('password'),
            'profile_picture' => 'https://api.dicebear.com/7.x/adventurer/svg?seed=Jane',
            'role' => 'Admin',
            'is_active' => 1,
        ]);
        DB::table('users')->insert([
            'name' => 'Jack',
            'email' => 'jack.doe@gmail.com',
            'password' => Hash::make('password'),
            'profile_picture' => 'https://api.dicebear.com/7.x/adventurer/svg?seed=Jack',
            'role' => 'User',
            'is_active' => 1,
        ]);
        // The idea here is to create the data for each table independently,
        // and only after these are created, manage the relationships so as not to have duplicate data

        Category::factory()->count(8)->create();
        $tags = Tag::factory()->count(5)->create();
        $users = User::factory()->count(15)->create();
        $gigs = Gig::factory()->count(100)->create();

        // No need here to generate reviews because it is managed in the GigFactory
        // No need here for foreach to associate users id and gig id with reviews because it is managed in the ReviewFactory

        foreach ($gigs as $gig) {
            // Here attach is used because of the many-to-many relationship between gigs and tags
            $gig->tags()->attach($tags->random(rand(1, 5))->pluck('id')->toArray());
            $gig->favoritedBy()->attach($users->random(rand(1, 10))->pluck('id')->toArray());

            // Here associate is used because of the relationship gig has one user
            // $gig->user()->associate($users->random());
            // No need to use it here because the id categories and the users id per gig are already managed in the GigFactory,
            // but leaving the line because it is interesting to know
        }
    }
}
