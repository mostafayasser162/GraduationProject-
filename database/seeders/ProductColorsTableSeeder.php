<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ProductColorsTableSeeder extends Seeder
{
    public function run()
    {
        $productIds = DB::table('products')->pluck('id')->toArray();

        if (empty($productIds)) {
            $this->command->warn('No products found to seed colors for.');
            return;
        }

        $colors = [
            ['name' => 'Red', 'code' => '#FF0000'],
            ['name' => 'Green', 'code' => '#00FF00'],
            ['name' => 'Blue', 'code' => '#0000FF'],
            ['name' => 'Black', 'code' => '#000000'],
            ['name' => 'White', 'code' => '#FFFFFF'],
            ['name' => 'Yellow', 'code' => '#FFFF00'],
        ];

        foreach ($productIds as $productId) {
            $randomColors = array_rand($colors, rand(1, 3)); // pick 1 to 3 random colors
            foreach ((array) $randomColors as $index) {
                DB::table('product_colors')->insert([
                    'product_id' => $productId,
                    'color_name' => $colors[$index]['name'],
                    'color_code' => $colors[$index]['code'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        $this->command->info('Product colors seeded successfully.');
    }
}
