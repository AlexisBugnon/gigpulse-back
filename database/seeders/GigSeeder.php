<?php

namespace Database\Seeders;


use App\Models\Gig;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class GigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Gig::factory()->count(25)->create();
    }
}
