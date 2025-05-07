<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Address;
use App\Models\User;
use Faker\Factory as Faker;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        
        // Get some users from the database
        $users = User::all();

        // Loop through each user and create a random address for them
        foreach ($users as $user) {
            // Create multiple random addresses for each user (adjust the number as needed)
            for ($i = 0; $i < 3; $i++) {
                Address::create([
                    'user_id' => $user->id,
                    'lat' => $faker->latitude(30, 32), // Latitude in Cairo range (can be adjusted)
                    'lng' => $faker->longitude(31, 32), // Longitude in Cairo range (can be adjusted)
                    'address' => $faker->address,
                    'city' => $faker->city,
                ]);
            }
        }
    }
}
