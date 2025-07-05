@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 text-primary-emphasis">Tableau de bord</h1>
        <a href="{{ route('products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Ajouter un produit
        </a>
    </div>
    <p class="lead text-muted mb-4">
        Bonjour, <strong class="text-dark">{{ Auth::user()->name }}</strong> ! Voici un aperçu de vos produits.
    </p>

    <!-- Stat Cards -->
    <div class="row g-4">
        <div class="col-md-6 col-xl-3">
            <div class="card card-stat bg-primary-custom h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="card-subtitle mb-1">Produits GM</h6>
                        <h4 class="card-title fw-bold">{{ $stats['gm'] }}</h4>
                    </div>
                    <i class="fas fa-box-open fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card card-stat bg-success-custom h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="card-subtitle mb-1">Produits PM</h6>
                        <h4 class="card-title fw-bold">{{ $stats['pm'] }}</h4>
                    </div>
                    <i class="fas fa-boxes-stacked fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-2">
            <div class="card card-stat bg-info-custom h-100">
                <div class="card-body text-center">
                    <h6 class="card-subtitle mb-1">Épicée</h6>
                    <h4 class="card-title fw-bold">{{ $stats['spicy'] }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-2">
            <div class="card card-stat bg-warning-custom h-100">
                <div class="card-body text-center">
                    <h6 class="card-subtitle text-dark mb-1">Pimentée</h6>
                    <h4 class="card-title fw-bold text-dark">{{ $stats['hot'] }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-xl-2">
            <div class="card card-stat bg-secondary h-100">
                <div class="card-body text-center">
                    <h6 class="card-subtitle mb-1">Nature</h6>
                    <h4 class="card-title fw-bold">{{ $stats['natural'] }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Products Table -->
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 text-primary-emphasis">Produits Récents</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th scope="col">Nom d'utilisateur dans le mail</th>
                            <th scope="col">Famille</th>
                            <th scope="col">Type</th>
                            <th scope="col">Prix</th>
                            <th scope="col">Date Ajout</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentProducts as $product)
                            <tr>
                                <td class="fw-bold">{{ $product->name }}</td>
                                <td><span class="badge bg-{{ $product->family == 'GM' ? 'primary' : 'success' }} bg-opacity-75">{{ $product->family }}</span></td>
                                <td>{{ $product->type }}</td>
                                <td>{{ number_format($product->price, 2, ',', ' ') }} Ariary</td>
                                <td>{{ $product->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Aucun produit récent trouvé.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white text-center py-3">
            <a href="{{ route('products.index') }}">Voir tous les produits &rarr;</a>
        </div>
    </div>

    <!-- Consolidated Chart -->
        <!-- Charts Section -->
    <div class="row g-4 mt-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-primary-emphasis">Répartition et Statut des Produits</h5>
                </div>
                <div class="card-body">
                    <canvas id="productStatsChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-primary-emphasis">Pourcentage Payé / Non Payé</h5>
                </div>
                <div class="card-body">
                    <canvas id="paymentPercentageChart"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const gmCount = {{ $gmCount }};
        const pmCount = {{ $pmCount }};
        const paidCount = {{ $paidCount }};
        const unpaidCount = {{ $unpaidCount }};
        const totalProducts = {{ $totalProducts }};

        // Chart 1: Combined Bar Chart for Product Family and Payment Status
        const productStatsCtx = document.getElementById('productStatsChart').getContext('2d');
        new Chart(productStatsCtx, {
            type: 'bar',
            data: {
                labels: ['Produits GM', 'Produits PM', 'Produits Payés', 'Produits Non Payés'],
                datasets: [{
                    label: 'Nombre de Produits',
                    data: [gmCount, pmCount, paidCount, unpaidCount],
                    backgroundColor: [
                        'rgba(74, 144, 226, 0.8)', // GM (Blue)
                        'rgba(40, 167, 69, 0.8)',  // PM (Green)
                        'rgba(40, 167, 69, 0.8)',  // Payé (Green)
                        'rgba(220, 53, 69, 0.8)'   // Non Payé (Red)
                    ],
                    borderColor: [
                        'rgba(74, 144, 226, 1)',
                        'rgba(40, 167, 69, 1)',
                        'rgba(40, 167, 69, 1)',
                        'rgba(220, 53, 69, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false // Hide legend if labels are self-explanatory
                    },
                    title: {
                        display: true,
                        text: 'Répartition et Statut des Produits'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Quantité'
                        }
                    }
                }
            }
        });

        // Chart 2: Doughnut Chart for Payment Percentage
        const paymentPercentageCtx = document.getElementById('paymentPercentageChart').getContext('2d');
        const paidPercentage = totalProducts > 0 ? (paidCount / totalProducts * 100).toFixed(2) : 0;
        const unpaidPercentage = totalProducts > 0 ? (unpaidCount / totalProducts * 100).toFixed(2) : 0;

        new Chart(paymentPercentageCtx, {
            type: 'doughnut',
            data: {
                labels: ['Payé (%)', 'Non Payé (%)'],
                datasets: [{
                    data: [paidPercentage, unpaidPercentage],
                    backgroundColor: ['#28a745', '#dc3545'], // Green for Paid, Red for Unpaid
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Pourcentage de produits payés / non payés'
                    }
                }
            }
        });
    });
</script>
@endpush
