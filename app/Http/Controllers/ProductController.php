<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Affiche le tableau de bord avec les statistiques des produits.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        $gmCount = Product::where('family', 'GM')->count();
        $pmCount = Product::where('family', 'PM')->count();
        $paidCount = Product::where('status', 'Payer')->count();
        $unpaidCount = Product::where('status', 'Non Payer')->count();
        $totalProducts = Product::count();

        $spicyGmStock = Product::where('type', 'Épicée')->where('family', 'GM')->sum('quantity');
        $spicyPmStock = Product::where('type', 'Épicée')->where('family', 'PM')->sum('quantity');
        $hotGmStock = Product::where('type', 'Pimentée')->where('family', 'GM')->sum('quantity');
        $hotPmStock = Product::where('type', 'Pimentée')->where('family', 'PM')->sum('quantity');
        $naturalGmStock = Product::where('type', 'Nature')->where('family', 'GM')->sum('quantity');
        $naturalPmStock = Product::where('type', 'Nature')->where('family', 'PM')->sum('quantity');

        $totalSpicyStock = $spicyGmStock + $spicyPmStock;
        $totalHotStock = $hotGmStock + $hotPmStock;
        $totalNaturalStock = $naturalGmStock + $naturalPmStock;
        $totalStock = Product::sum('quantity');

        $stats = [
            'gm' => $gmCount,
            'pm' => $pmCount,
            'spicy' => $totalSpicyStock,
            'hot' => $totalHotStock,
            'natural' => $totalNaturalStock,
            'total_stock' => $totalStock,
        ];

        $recentProducts = Product::latest()->take(5)->get();

        return view('dashboard', compact('stats', 'recentProducts', 'gmCount', 'pmCount', 'paidCount', 'unpaidCount', 'totalProducts',
            'totalSpicyStock', 'totalHotStock', 'totalNaturalStock', 'totalStock',
            'spicyGmStock', 'spicyPmStock', 'hotGmStock', 'hotPmStock', 'naturalGmStock', 'naturalPmStock'
        ));
    }

    /**
     * Affiche la liste des produits.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Product::query();

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_name', 'like', '%' . $request->search . '%');
            });
        }

        // Filters
        if ($request->filled('family')) {
            $query->where('family', $request->family);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $products = $query->latest()->paginate(10)->withQueryString();

        return view('products.index', compact('products'));
    }

    /**
     * Affiche le formulaire de création d'un produit.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Enregistre un nouveau produit dans la base de données.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'customer_name' => 'required|string|max:255',
            'family' => 'required|in:GM,PM',
            'type' => 'required|in:Épicée,Pimentée,Nature',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'status' => 'required|in:Payer,Non Payer',
            'description' => 'nullable|string',
        ]);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'customer_name' => 'required|string|max:255',
            'family' => 'required|in:GM,PM',
            'type' => 'required|in:Épicée,Pimentée,Nature',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'status' => 'required|in:Payer,Non Payer',
            'description' => 'nullable|string',
        ]);

        Product::create($validatedData);

        return redirect()->route('products.index')
            ->with('success', 'Produit ajouté avec succès.');
    }

    /**
     * Affiche le formulaire de modification d'un produit.
     *
     * @return \Illuminate\View\View
     */
    /**
     * Affiche les détails d'un produit spécifique.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\View\View
     */
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    /**
     * Affiche le formulaire de modification d'un produit.
     *
     * @return \Illuminate\View\View
     */
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Met à jour un produit dans la base de données.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'customer_name' => 'required|string|max:255',
            'family' => 'required|in:GM,PM',
            'type' => 'required|in:Épicée,Pimentée,Nature',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'status' => 'required|in:Payer,Non Payer',
            'description' => 'nullable|string',
        ]);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'customer_name' => 'required|string|max:255',
            'family' => 'required|in:GM,PM',
            'type' => 'required|in:Épicée,Pimentée,Nature',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'status' => 'required|in:Payer,Non Payer',
            'description' => 'nullable|string',
        ]);

        $product->update($validatedData);

        return redirect()->route('products.index')
            ->with('success', 'Produit mis à jour avec succès.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Produit supprimé avec succès.');
    }
}
