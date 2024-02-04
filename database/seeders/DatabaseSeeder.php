<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();
        DB::table('users')->insert([
            'name' => 'John',
            'email' => 'john.doe@gmail.com',
            'password' => Hash::make('password'),
            'profile_picture' => 'https://api.dicebear.com/7.x/adventurer/svg?seed=John',
            'role' =>'Super admin',
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
    }
}


