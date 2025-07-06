<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StockController extends Controller
{
    public function index()
    {
        $stocks = Product::select(
                'family',
                'type',
                // "Stock physique total" est la somme de TOUTES les quantités pour ce type
                DB::raw("SUM(quantity) as total_quantity"),
                // "Vendu" est la somme des quantités assignées à un client
                DB::raw("SUM(CASE WHEN customer_name != '--STOCK--' THEN quantity ELSE 0 END) as total_sold")
            )
            ->groupBy('family', 'type')
            ->orderBy('family')
            ->orderBy('type')
            ->get();

        return view('stock.index', compact('stocks'));
    }

    public function addStock(Request $request)
    {
        $request->validate([
            'family' => 'required|in:GM,PM',
            'type' => 'required|in:Épicée,Pimentée,Nature',
            'quantity' => 'required|integer|min:1',
        ]);

        // On utilise un produit "fictif" pour représenter une entrée de stock
        Product::create([
            'family' => $request->family,
            'type' => $request->type,
            'quantity' => $request->quantity,
            'customer_name' => '--STOCK--', // Marqueur pour le stock brut
            'status' => 'Payer', // Non applicable, mais requis par la DB
            'price' => 0, // Non applicable
        ]);

        return redirect()->route('stock.index')->with('success', 'Stock ajouté avec succès.');
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',
            'new_sold_quantity' => 'nullable|integer|min:0',
        ]);

        $product->quantity = $request->input('quantity');

        if ($request->filled('new_sold_quantity')) {
            $product->quantity_sold += $request->input('new_sold_quantity');
        }
        
        // Assurer que la quantité vendue ne dépasse pas la quantité totale
        if ($product->quantity_sold > $product->quantity) {
            $product->quantity_sold = $product->quantity;
        }

        $product->save();

        return redirect()->route('stock.index')->with('success', 'Stock mis à jour avec succès.');
    }

    public function updateAggregatedStock(Request $request)
    {
        $request->validate([
            'family' => 'required|in:GM,PM',
            'type' => 'required|in:Épicée,Pimentée,Nature',
            'new_total_quantity' => 'required|integer|min:0',
        ]);

        $family = $request->input('family');
        $type = $request->input('type');
        $newTotalQuantity = $request->input('new_total_quantity');

        // Get current total stock for this family and type (only raw stock entries)
        $currentRawStock = Product::where('family', $family)
                                  ->where('type', $type)
                                  ->where('customer_name', '--STOCK--')
                                  ->sum('quantity');

        // Get total sold quantity for this family and type
        $totalSold = Product::where('family', $family)
                            ->where('type', $type)
                            ->where('customer_name', '!=', '--STOCK--')
                            ->sum('quantity');

        // Calculate the difference needed
        $difference = $newTotalQuantity - $currentRawStock;

        try {
            DB::transaction(function () use ($family, $type, $difference, $newTotalQuantity, $totalSold) {
                if ($difference > 0) {
                    // Si le nouveau total est supérieur, ajouter une nouvelle entrée de stock brut
                    Product::create([
                        'family' => $family,
                        'type' => $type,
                        'quantity' => $difference,
                        'customer_name' => '--STOCK--',
                        'status' => 'Payer', // Par défaut, non pertinent pour le stock brut
                        'price' => 0, // Par défaut, non pertinent
                    ]);
                } elseif ($difference < 0) {
                    // Si le nouveau total est inférieur, réduire les entrées de stock brut existantes
                    $quantityToReduce = abs($difference);

                    // S'assurer que nous ne réduisons pas en dessous de la quantité vendue
                    if (($newTotalQuantity - $totalSold) < 0) {
                        throw new \Exception('Impossible de réduire le stock en dessous de la quantité vendue.');
                    }

                    $rawStockEntries = Product::where('family', $family)
                                              ->where('type', $type)
                                              ->where('customer_name', '--STOCK--')
                                              ->orderBy('created_at', 'asc') // Réduire les entrées les plus anciennes en premier
                                              ->get();

                    foreach ($rawStockEntries as $entry) {
                        if ($quantityToReduce <= 0) break;

                        if ($entry->quantity <= $quantityToReduce) {
                            $quantityToReduce -= $entry->quantity;
                            $entry->delete();
                        } else {
                            $entry->quantity -= $quantityToReduce;
                            $entry->save();
                            $quantityToReduce = 0;
                        }
                    }
                }
            });
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour du stock agrégé: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }

        // Return the new total quantity for UI update
        return response()->json(['new_total_quantity' => $newTotalQuantity]);
    }
}
