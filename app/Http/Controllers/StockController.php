<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Display a listing of the stock.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $stockData = Product::select('type', 'family', 'status')
            ->selectRaw('SUM(quantity) as total_quantity')
            ->groupBy('type', 'family', 'status')
            ->orderBy('type')
            ->orderBy('family')
            ->get();

        return view('stock.index', compact('stockData'));
    }

    /**
     * Update the specified product's stock in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        $product->update($validatedData);

        return redirect()->route('stock.index')
            ->with('success', 'Stock mis à jour avec succès.');
    }
}