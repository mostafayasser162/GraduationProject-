<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Package;
use Carbon\Carbon;

class PackageSeeder extends Seeder
{
    public function run()
    {
        // Clear existing packages
        Package::truncate();

        // Basic Package
        Package::create([
            'name' => 'Basic Package',
            'description' => 'Basic package for startups with essential features',
            'price' => 1350.00,
            'duration' => 'quarterly',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Package::create([
            'name' => 'Basic Package',
            'description' => 'Basic package for startups with essential features',
            'price' => 4000.00,
            'duration' => 'yearly',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Pro Package (Marketing)
        Package::create([
            'name' => 'Pro Package (Marketing)',
            'description' => 'Pro package with marketing features for startups',
            'price' => 2000.00,
            'duration' => 'quarterly',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Package::create([
            'name' => 'Pro Package (Marketing)',
            'description' => 'Pro package with marketing features for startups',
            'price' => 6000.00,
            'duration' => 'yearly',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Pro Package (Supplychain)
        Package::create([
            'name' => 'Pro Package (Supplychain)',
            'description' => 'Pro package with supply chain features for startups',
            'price' => 2000.00,
            'duration' => 'quarterly',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Package::create([
            'name' => 'Pro Package (Supplychain)',
            'description' => 'Pro package with supply chain features for startups',
            'price' => 6000.00,
            'duration' => 'yearly',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Premium Package
        Package::create([
            'name' => 'Premium Package',
            'description' => 'Premium package with full access to all features',
            'price' => 2500.00,
            'duration' => 'quarterly',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Package::create([
            'name' => 'Premium Package',
            'description' => 'Premium package with full access to all features',
            'price' => 7500.00,
            'duration' => 'yearly',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
