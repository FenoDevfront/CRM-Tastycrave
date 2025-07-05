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

    <!-- Key Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="card card-stat bg-primary-custom text-white h-100">
                <div class="card-body d-flex flex-column align-items-center justify-content-center text-center">
                    <i class="fas fa-box-open fa-4x mb-3 text-white-75"></i>
                    <h6 class="card-subtitle text-white-50 mb-1">Produits GM</h6>
                    <h4 class="card-title fw-bold display-5">{{ $stats['gm'] }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card card-stat bg-success-custom text-white h-100">
                <div class="card-body d-flex flex-column align-items-center justify-content-center text-center">
                    <i class="fas fa-boxes-stacked fa-4x mb-3 text-white-75"></i>
                    <h6 class="card-subtitle text-white-50 mb-1">Produits PM</h6>
                    <h4 class="card-title fw-bold display-5">{{ $stats['pm'] }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card card-stat bg-info-custom text-white h-100">
                <div class="card-body d-flex flex-column align-items-center justify-content-center text-center">
                    <i class="fas fa-check-circle fa-4x mb-3 text-white-75"></i>
                    <h6 class="card-subtitle text-white-50 mb-1">Produits Payés</h6>
                    <h4 class="card-title fw-bold display-5">{{ $paidCount }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card card-stat bg-danger-custom text-white h-100">
                <div class="card-body d-flex flex-column align-items-center justify-content-center text-center">
                    <i class="fas fa-times-circle fa-4x mb-3 text-white-75"></i>
                    <h6 class="card-subtitle text-white-50 mb-1">Produits Non Payés</h6>
                    <h4 class="card-title fw-bold display-5">{{ $unpaidCount }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card card-stat bg-secondary text-white h-100">
                <div class="card-body d-flex flex-column align-items-center justify-content-center text-center">
                    <i class="fas fa-warehouse fa-4x mb-3 text-white-75"></i>
                    <h6 class="card-subtitle text-white-50 mb-1">Total Stock</h6>
                    <h4 class="card-title fw-bold display-5">{{ $totalStock }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card card-stat bg-dark text-white h-100">
                <div class="card-body d-flex flex-column align-items-center justify-content-center text-center">
                    <i class="fas fa-pepper-hot fa-4x mb-3 text-white-75"></i>
                    <h6 class="card-subtitle text-white-50 mb-1">Stock Épicée</h6>
                    <h4 class="card-title fw-bold display-5">{{ $totalSpicyStock }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card card-stat bg-warning-custom text-dark h-100">
                <div class="card-body d-flex flex-column align-items-center justify-content-center text-center">
                    <i class="fas fa-fire fa-4x mb-3 text-dark-75"></i>
                    <h6 class="card-subtitle text-dark-50 mb-1">Stock Pimentée</h6>
                    <h4 class="card-title fw-bold display-5 text-dark">{{ $totalHotStock }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card card-stat bg-light text-dark h-100">
                <div class="card-body d-flex flex-column align-items-center justify-content-center text-center">
                    <i class="fas fa-leaf fa-4x mb-3 text-muted"></i>
                    <h6 class="card-subtitle text-muted mb-1">Stock Nature</h6>
                    <h4 class="card-title fw-bold display-5">{{ $totalNaturalStock }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row g-4 mt-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-primary-emphasis">Répartition et Statut des Produits</h5>
                </div>
                <div class="card-body">
                    <canvas id="productStatsChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-primary-emphasis">Pourcentage Payé / Non Payé</h5>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <canvas id="paymentPercentageChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card border-0 shadow-sm h-100 mt-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-primary-emphasis">Stock par Type et Famille (GM vs PM)</h5>
                </div>
                <div class="card-body">
                    <canvas id="stockByTypeAndFamilyChart"></canvas>
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
        const totalSpicyStock = {{ $totalSpicyStock }};
        const totalHotStock = {{ $totalHotStock }};
        const totalNaturalStock = {{ $totalNaturalStock }};
        const totalStock = {{ $totalStock }};

        const spicyGmStock = {{ $spicyGmStock }};
        const spicyPmStock = {{ $spicyPmStock }};
        const hotGmStock = {{ $hotGmStock }};
        const hotPmStock = {{ $hotPmStock }};
        const naturalGmStock = {{ $naturalGmStock }};
        const naturalPmStock = {{ $naturalPmStock }};

        // Chart 1: Combined Bar Chart for Product Family, Payment Status, and Total Stock by Type
        const productStatsCtx = document.getElementById('productStatsChart').getContext('2d');
        new Chart(productStatsCtx, {
            type: 'bar',
            data: {
                labels: ['Produits GM', 'Produits PM', 'Produits Payés', 'Produits Non Payés', 'Stock Épicée', 'Stock Pimentée', 'Stock Nature', 'Total Stock'],
                datasets: [{
                    label: 'Nombre de Produits / Quantité en Stock',
                    data: [gmCount, pmCount, paidCount, unpaidCount, totalSpicyStock, totalHotStock, totalNaturalStock, totalStock],
                    backgroundColor: [
                        'rgba(255, 127, 80, 0.8)', // GM (Coral - placeholder for logo color)
                        'rgba(40, 167, 69, 0.8)',  // PM (Green)
                        'rgba(40, 167, 69, 0.8)',  // Payé (Green)
                        'rgba(220, 53, 69, 0.8)',   // Non Payé (Red)
                        'rgba(255, 193, 7, 0.8)', // Épicée (Yellow)
                        'rgba(253, 126, 20, 0.8)', // Pimentée (Orange)
                        'rgba(23, 162, 184, 0.8)', // Nature (Cyan)
                        'rgba(108, 117, 125, 0.8)' // Total Stock (Grey)
                    ],
                    borderColor: [
                        'rgba(255, 127, 80, 1)',
                        'rgba(40, 167, 69, 1)',
                        'rgba(40, 167, 69, 1)',
                        'rgba(220, 53, 69, 1)',
                        'rgba(255, 193, 7, 1)',
                        'rgba(253, 126, 20, 1)',
                        'rgba(23, 162, 184, 1)',
                        'rgba(108, 117, 125, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Statistiques Générales des Produits et Stock'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Quantité / Nombre'
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
                labels: ['Payé (' + paidPercentage + '%)', 'Non Payé (' + unpaidPercentage + '%)'],
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

        // Chart 3: Stacked Bar Chart for Stock by Type and Family
        const stockByTypeAndFamilyCtx = document.getElementById('stockByTypeAndFamilyChart').getContext('2d');
        new Chart(stockByTypeAndFamilyCtx, {
            type: 'bar',
            data: {
                labels: ['Épicée', 'Pimentée', 'Nature'],
                datasets: [
                    {
                        label: 'Stock GM',
                        data: [spicyGmStock, hotGmStock, naturalGmStock],
                        backgroundColor: 'rgba(74, 144, 226, 0.8)', // GM (Blue)
                        borderColor: 'rgba(74, 144, 226, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Stock PM',
                        data: [spicyPmStock, hotPmStock, naturalPmStock],
                        backgroundColor: 'rgba(40, 167, 69, 0.8)',  // PM (Green)
                        borderColor: 'rgba(40, 167, 69, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Stock par Type et Famille (GM vs PM)'
                    }
                },
                scales: {
                    x: {
                        stacked: true,
                        title: {
                            display: true,
                            text: 'Type de Produit'
                        }
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Quantité en Stock'
                        }
                    }
                }
            }
        });
    });
</script>
@endpush