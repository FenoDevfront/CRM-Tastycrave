<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('fr_FR');
        $families = ['GM', 'PM'];
        $types = ['Épicée', 'Pimentée', 'Nature'];
        $statuses = ['Payer', 'Non Payer'];

        // Clear the table before seeding
        Product::truncate();

        for ($i = 0; $i < 50; $i++) { // Create 50 products
            Product::create([
                'name' => 'Produit ' . $faker->unique()->word,
                'customer_name' => $faker->name,
                'family' => $faker->randomElement($families),
                'type' => $faker->randomElement($types),
                'price' => $faker->randomFloat(2, 5, 100),
                'quantity' => $faker->numberBetween(1, 50),
                'status' => $faker->randomElement($statuses),
                'description' => $faker->sentence,
            ]);
        }
    }
}