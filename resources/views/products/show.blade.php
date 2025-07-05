@extends('layouts.app')

@section('title', 'Détails du produit')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 text-primary-emphasis">Détails du produit : {{ $product->name }}</h1>
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 text-primary-emphasis">Informations sur le produit</h5>
        </div>
        <div class="card-body p-4">
            <div class="row mb-3">
                <div class="col-md-4"><strong>Nom du produit:</strong></div>
                <div class="col-md-8">{{ $product->name }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4"><strong>Nom du client:</strong></div>
                <div class="col-md-8">{{ $product->customer_name }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4"><strong>Famille:</strong></div>
                <div class="col-md-8">{{ $product->family }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4"><strong>Type:</strong></div>
                <div class="col-md-8">{{ $product->type }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4"><strong>Prix:</strong></div>
                <div class="col-md-8">{{ number_format($product->price, 2, ',', ' ') }} Ariary</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4"><strong>Quantité:</strong></div>
                <div class="col-md-8">{{ $product->quantity }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4"><strong>Statut:</strong></div>
                <div class="col-md-8">{{ $product->status }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4"><strong>Description:</strong></div>
                <div class="col-md-8">{{ $product->description ?? 'N/A' }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4"><strong>Date de création:</strong></div>
                <div class="col-md-8">{{ $product->created_at->format('d/m/Y H:i') }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4"><strong>Dernière mise à jour:</strong></div>
                <div class="col-md-8">{{ $product->updated_at->format('d/m/Y H:i') }}</div>
            </div>
            <div class="mt-4">
                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i>Modifier le produit
                </a>
                <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash-alt me-2"></i>Supprimer le produit
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
@endpush
