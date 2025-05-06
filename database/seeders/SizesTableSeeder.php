<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class SizesTableSeeder extends Seeder
{
    public function run()
    {
        $startupIds = DB::table('startups')->pluck('id')->toArray(); // assuming you have a 'startups' table

        if (empty($startupIds)) {
            $this->command->warn('No startups found to link sizes to.');
            return;
        }

        $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];

        foreach ($sizes as $size) {
            DB::table('sizes')->insert([
                'startup_id' => $startupIds[array_rand($startupIds)],
                'size' => $size,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        $this->command->info('Sizes seeded successfully.');
    }
}
