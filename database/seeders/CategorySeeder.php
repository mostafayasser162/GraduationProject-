<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Faker\Factory as Faker;
use Carbon\Carbon;

class CategorySeeder extends Seeder
{
    public function run()
    {
        // Create a new Faker instance
        $faker = Faker::create();

        // Seed 10 random categories (you can change the number as per your requirement)
        foreach (range(1, 10) as $index) {
            Category::create([
                'name' => $faker->word, // Random word as category name
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
