<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

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
}
