@extends('layouts.app')

@section('title', 'Liste des produits')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 text-primary-emphasis">Gestion des Produits</h1>
        <a href="{{ route('products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Ajouter un produit
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 text-primary-emphasis">Tous les produits</h5>
        </div>
        <div class="card-body">
            <!-- Search and Filter -->
            <form action="{{ route('products.index') }}" method="GET" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Rechercher par nom ou client..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="family" class="form-select">
                            <option value="">Toute famille</option>
                            <option value="GM" {{ request('family') == 'GM' ? 'selected' : '' }}>GM</option>
                            <option value="PM" {{ request('family') == 'PM' ? 'selected' : '' }}>PM</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="type" class="form-select">
                            <option value="">Tout type</option>
                            <option value="Épicée" {{ request('type') == 'Épicée' ? 'selected' : '' }}>Épicée</option>
                            <option value="Pimentée" {{ request('type') == 'Pimentée' ? 'selected' : '' }}>Pimentée</option>
                            <option value="Nature" {{ request('type') == 'Nature' ? 'selected' : '' }}>Nature</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">Tout statut</option>
                            <option value="Payer" {{ request('status') == 'Payer' ? 'selected' : '' }}>Payé</option>
                            <option value="Non Payer" {{ request('status') == 'Non Payer' ? 'selected' : '' }}>Non Payé</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nom Produit</th>
                            <th>Client</th>
                            <th>Famille</th>
                            <th>Type</th>
                            <th>Prix</th>
                            <th>Quantité</th>
                            <th>Statut</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr>
                                <td class="fw-bold">{{ $product->name }}</td>
                                <td>{{ $product->customer_name }}</td>
                                <td><span class="badge bg-{{ $product->family == 'GM' ? 'primary' : 'success' }} bg-opacity-75">{{ $product->family }}</span></td>
                                <td>{{ $product->type }}</td>
                                <td>{{ number_format($product->price, 2, ',', ' ') }} Ariary</td>
                                <td>{{ $product->quantity }}</td>
                                <td>
                                    <span class="badge bg-{{ $product->status == 'Payer' ? 'success' : 'danger' }}">
                                        {{ $product->status }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-outline-primary me-1" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    Aucun produit trouvé correspondant à vos critères.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4 d-flex justify-content-center">
                {{ $products->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
@endpush