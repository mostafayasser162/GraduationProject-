<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Create 10 random products
        for ($i = 0; $i < 10; $i++) {
            Product::create([
                'startup_id' => rand(3, 22), // Random startup_id, assuming there are startups already created
                'name' => $faker->word,
                'description' => $faker->paragraph,
                'price' => $faker->randomFloat(2, 100, 1000), // Random price between 100 and 1000
                'sub_category_id' => rand(1, 20), // Random startup_id, assuming there are startups already created
                'stock' => rand(1, 200), // Random startup_id, assuming there are startups already created
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
