<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSizesTableSeeder extends Seeder
{
    public function run()
    {
        $products = DB::table('products')->pluck('id')->toArray();
        $sizes = DB::table('sizes')->pluck('id')->toArray();

        foreach ($products as $productId) {
            // Randomly assign 1-3 sizes to each product
            $numSizes = rand(1, 3);
            $selectedSizes = array_rand($sizes, $numSizes);

            if (!is_array($selectedSizes)) {
                $selectedSizes = [$selectedSizes];
            }

            foreach ($selectedSizes as $sizeIndex) {
                DB::table('product_sizes')->insert([
                    'product_id' => $productId,
                    'size_id' => $sizes[$sizeIndex],
                    'price' => rand(1000, 5000) / 100, // Random price between 10 and 50
                    'stock' => rand(5, 50),
                    'discount_percentage' => rand(0, 1) ? rand(5, 30) : null, // Random discount between 5-30% or null
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
