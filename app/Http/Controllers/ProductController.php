<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function dashboard()
    {
        // 1. Données pour le graphique du stock disponible
        $stockData = Product::select(
                'family',
                'type',
                DB::raw("SUM(quantity) as total_quantity"),
                DB::raw("SUM(CASE WHEN customer_name != '--STOCK--' THEN quantity ELSE 0 END) as total_sold")
            )
            ->groupBy('family', 'type')
            ->get();

        // Calcul du disponible pour le graphique et la carte principale
        $stockData->each(function ($item) {
            $item->available_stock = $item->total_quantity - $item->total_sold;
        });
        $totalAvailable = $stockData->sum('available_stock');

        // 2. Données pour le graphique des ventes mensuelles
        $monthlySales = Product::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw("SUM(price) as total_sales")
            )
            ->where('customer_name', '!=', '--STOCK--')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();
        
        // Préparation des données pour Chart.js
        $salesChartData = [
            'labels' => ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
            'data' => array_fill(0, 12, 0)
        ];

        foreach ($monthlySales as $sale) {
            $monthIndex = (int)substr($sale->month, 5) - 1;
            if ($monthIndex >= 0 && $monthIndex < 12) {
                $salesChartData['data'][$monthIndex] = $sale->total_sales;
            }
        }

        // 3. Données pour les autres sections
        $lastProducts = Product::where('customer_name', '!=', '--STOCK--')->latest()->take(5)->get();
        $statusCounts = Product::where('customer_name', '!=', '--STOCK--')->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $totalProducts = $statusCounts->sum();
        $statusPercentages = [
            'Payer' => $totalProducts > 0 ? ($statusCounts->get('Payer', 0) / $totalProducts) * 100 : 0,
            'Non Payer' => $totalProducts > 0 ? ($statusCounts->get('Non Payer', 0) / $totalProducts) * 100 : 0,
        ];

        return view('dashboard', compact('stockData', 'totalAvailable', 'lastProducts', 'statusPercentages', 'salesChartData'));
    }

    public function index(Request $request)
    {
        $query = Product::where('customer_name', '!=', '--STOCK--');

        // Filtrage par recherche
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('customer_name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Filtrage par famille
        if ($request->filled('family')) {
            $query->where('family', $request->family);
        }

        // Filtrage par type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filtrage par statut
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $products = $query->latest()->paginate(10);

        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'family' => 'required|in:GM,PM',
            'type' => 'required|in:Épicée,Pimentée,Nature',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'customer_name' => 'required|string|max:255',
            'status' => 'required|in:Payer,Non Payer',
            'description' => 'nullable|string',
        ]);

        Product::create($request->all());

        return redirect()->route('products.index')
            ->with('success', 'Produit ajouté avec succès.');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'family' => 'required|in:GM,PM',
            'type' => 'required|in:Épicée,Pimentée,Nature',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'customer_name' => 'required|string|max:255',
            'status' => 'required|in:Payer,Non Payer',
            'description' => 'nullable|string',
        ]);

        $product->update($request->all());

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
