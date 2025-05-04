<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Package;
use Faker\Factory as Faker;
use Carbon\Carbon;

class PackageSeeder extends Seeder
{
    public function run()
    {
        // Create a new Faker instance
        $faker = Faker::create();

        // Seed 10 random packages (you can change the number as per your requirement)
        foreach (range(1, 10) as $index) {
            Package::create([
                'name' => $faker->word . ' Package', // Random word + Package
                'description' => $faker->paragraph, // Random paragraph as description
                'price' => $faker->randomFloat(2, 50, 500), // Random price between 50 and 500
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
