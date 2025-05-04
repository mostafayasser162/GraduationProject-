<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
// use App\Models\SubCategory;
use App\Models\Category; // To link with categories table
use App\Models\Sub_category;
use Faker\Factory as Faker;
use Carbon\Carbon;

class SubCategorySeeder extends Seeder
{
    public function run()
    {
        // Create a new Faker instance
        $faker = Faker::create();

        // Get all categories to assign a random category_id to each subcategory
        $categories = Category::all();

        // Seed 20 random subcategories (you can change the number as per your requirement)
        foreach (range(1, 20) as $index) {
            Sub_category::create([
                'name' => $faker->word, // Random word as subcategory name
                'category_id' => $categories->random()->id, // Randomly pick a category
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
