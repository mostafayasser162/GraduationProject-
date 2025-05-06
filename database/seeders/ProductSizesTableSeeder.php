<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ProductSizesTableSeeder extends Seeder
{
    public function run()
    {
        // Get some random products, colors, and sizes
        $products = DB::table('products')->pluck('id')->toArray();
        $colors = DB::table('product_colors')->pluck('id')->toArray();
        $sizes = DB::table('sizes')->pluck('id')->toArray(); // Assuming you have this table

        if (empty($products) || empty($colors) || empty($sizes)) {
            $this->command->warn('Missing data in products, products_colors, or sizes table.');
            return;
        }

        foreach (range(1, 20) as $i) {
            DB::table('product_sizes')->insert([
                'product_id' => $products[array_rand($products)],
                'color_id' => $colors[array_rand($colors)],
                'size_id' => $sizes[array_rand($sizes)],
                'price' => rand(100, 500), // Random price
                'stock' => rand(10, 100),  // Random stock
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        $this->command->info('Product sizes seeded successfully.');
    }
}
