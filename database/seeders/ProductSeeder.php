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

        foreach (range(1, 50) as $index) {
            Product::create([
                'product_name' => $faker->word,
                'description' => $faker->sentence,
                'price' => $faker->randomFloat(2, 5, 100),
                'sku' => $faker->unique()->word,
                'quantity' => $faker->numberBetween(1, 100),
                'type' => $faker->word,
                'vendor' => $faker->company,
            ]);
        }
    }
}
