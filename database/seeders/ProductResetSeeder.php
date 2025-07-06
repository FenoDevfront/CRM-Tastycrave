<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductResetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Désactiver la vérification des clés étrangères pour la suppression
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // Vider complètement la table
        Product::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $families = ['GM', 'PM'];
        $types = ['Épicée', 'Pimentée', 'Nature'];
        $prices = ['GM' => 1500, 'PM' => 800];

        foreach ($families as $family) {
            foreach ($types as $type) {
                // Créer 3 produits vendus pour chaque combinaison
                for ($i = 1; $i <= 3; $i++) {
                    $quantity = rand(1, 5);
                    Product::create([
                        'family' => $family,
                        'type' => $type,
                        'quantity' => $quantity,
                        'price' => $quantity * $prices[$family],
                        'customer_name' => 'Client Fictif ' . $i . ' (' . $family . '/' . $type . ')',
                        'status' => rand(0, 1) ? 'Payer' : 'Non Payer',
                        'description' => 'Vente de test.',
                        'created_at' => now()->subDays(rand(1, 30)),
                        'updated_at' => now()->subDays(rand(1, 30)),
                    ]);
                }

                // Créer une entrée de stock brut correspondante
                // Assez de stock pour couvrir les ventes et avoir un surplus
                Product::create([
                    'family' => $family,
                    'type' => $type,
                    'quantity' => 50, // Stock initial pour chaque type
                    'customer_name' => '--STOCK--',
                    'status' => 'Payer',
                    'price' => 0,
                ]);
            }
        }
    }
}