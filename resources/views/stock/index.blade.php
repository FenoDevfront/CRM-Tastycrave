@extends('layouts.app')

@section('title', 'Gestion de Stock')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 text-primary-emphasis">Gestion de Stock</h1>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 text-primary-emphasis">Produits en Stock</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Type de Produit</th>
                            <th>Famille</th>
                            <th>Statut</th>
                            <th>Quantité Totale</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($stockData as $item)
                            <tr>
                                <td class="fw-bold">{{ $item->type }}</td>
                                <td><span class="badge bg-{{ $item->family == 'GM' ? 'primary' : 'success' }} bg-opacity-75">{{ $item->family }}</span></td>
                                <td>
                                    <span class="badge bg-{{ $item->status == 'Payer' ? 'success' : 'danger' }}">
                                        {{ $item->status == 'Payer' ? 'Livré' : 'Non Livré' }}
                                    </span>
                                </td>
                                <td>{{ $item->total_quantity }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    Aucune donnée de stock trouvée.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
@endpush
