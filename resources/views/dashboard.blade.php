@extends('layouts.app')

@section('title', 'Tableau de Bord')

@section('content')
<div class="container-fluid px-4">
    <h1 class="h2 text-primary-emphasis mb-4">Tableau de Bord</h1>

    <div class="row g-4 mb-4">
        <!-- Unités Disponibles par Type -->
        <div class="col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-primary-emphasis">Unités Disponibles</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($stockData->where('available_stock', '>', 0) as $stock)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-3 py-2">
                                <div class="d-flex align-items-center">
                                    @php
                                        $iconClass = '';
                                        if ($stock->type == 'Épicée') {
                                            $iconClass = 'fas fa-pepper-hot text-danger'; // Font Awesome icon for spicy
                                        } elseif ($stock->type == 'Pimentée') {
                                            $iconClass = 'fas fa-fire text-warning'; // Font Awesome icon for chili/pimentée
                                        } elseif ($stock->type == 'Nature') {
                                            $iconClass = 'fas fa-leaf text-success'; // Font Awesome icon for nature
                                        }
                                    @endphp
                                    @if($iconClass)
                                        <i class="{{ $iconClass }} me-2" style="font-size: 1.2rem;"></i>
                                    @endif
                                    <span class="badge bg-{{ $stock->family == 'GM' ? 'primary' : 'secondary' }} me-2">{{ $stock->family }}</span>
                                    {{ $stock->type }}
                                </div>
                                <span class="fw-bold fs-5">{{ max(0, $stock->available_stock) }}</span>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted py-4">
                                Aucun stock disponible.
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <!-- Graphique de Répartition du Stock -->
        <div class="col-lg-8 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-primary-emphasis">Stock Disponible par Type</h5>
                </div>
                <div class="card-body">
                    <div style="min-height: 250px;">
                        <canvas id="stockChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graphique des Ventes Mensuelles -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-primary-emphasis">Ventes Mensuelles ({{ now()->year }})</h5>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" style="min-height: 250px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Statut des Paiements -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-primary-emphasis">Statut des Paiements</h5>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <div style="position: relative; height:250px; width:250px">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Derniers Produits Ajoutés -->
    <div class="col-12">
        <h5 class="mb-3 text-primary-emphasis">Derniers Produits Ajoutés</h5>
        <div class="row g-3">
            @forelse($lastProducts as $product)
                <div class="col-lg col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-user-tie fa-2x me-3 text-muted"></i>
                                <div>
                                    <h6 class="card-title mb-0">{{ $product->customer_name }}</h6>
                                    <small class="text-muted">{{ $product->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                            <p class="mb-1">
                                <span class="badge bg-{{ $product->family == 'GM' ? 'primary' : 'success' }}">{{ $product->family }}</span>
                                <span class="badge bg-info text-dark">{{ $product->type }}</span>
                            </p>
                            <p class="fw-bold mb-0">{{ number_format($product->price, 0, ',', ' ') }} Ar</p>
                        </div>
                        <div class="card-footer bg-white border-0 pt-0">
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-outline-primary">Voir les détails</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card text-center py-4">
                        <p class="mb-0 text-muted">Aucun produit à afficher pour le moment.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Graphique du Stock Disponible
    const stockData = @json($stockData);
    const stockLabels = ['Épicée', 'Pimentée', 'Nature'];
    const gmData = [0, 0, 0];
    const pmData = [0, 0, 0];

    stockData.forEach(item => {
        const index = stockLabels.indexOf(item.type);
        if (index > -1) {
            const available = Math.max(0, item.available_stock);
            if (item.family === 'GM') {
                gmData[index] += available;
            } else if (item.family === 'PM') {
                pmData[index] += available;
            }
        }
    });

    new Chart(document.getElementById('stockChart'), {
        type: 'bar',
        data: {
            labels: stockLabels,
            datasets: [
                { label: 'GM', data: gmData, backgroundColor: '#a36628', borderRadius: 5 },
                { label: 'PM', data: pmData, backgroundColor: '#9e7b58', borderRadius: 5 }
            ]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } },
            scales: { x: { grid: { display: false } }, y: { beginAtZero: true } }
        }
    });

    // 2. Graphique des Ventes Mensuelles
    const salesChartData = @json($salesChartData);
    new Chart(document.getElementById('salesChart'), {
        type: 'line',
        data: {
            labels: salesChartData.labels,
            datasets: [{
                label: 'Chiffre d\'affaires',
                data: salesChartData.data,
                borderColor: '#a36628',
                backgroundColor: 'rgba(163, 102, 40, 0.2)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) { return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'MGA' }).format(value); }
                    }
                }
            }
        }
    });

    // 3. Graphique du Statut des Paiements
    const statusData = @json($statusPercentages);
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: ['Payé', 'Non Payé'],
            datasets: [{
                data: [statusData.Payer, statusData['Non Payer']],
                backgroundColor: ['#9e7b58', '#ceb79f'],
                borderColor: ['#9e7b58', '#ceb79f'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ' + context.parsed.toFixed(2) + '%';
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush
