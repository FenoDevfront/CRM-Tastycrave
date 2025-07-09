@extends('layouts.app')

@section('title', 'État du Stock')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 text-primary-emphasis">État du Stock</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStockModal">
            <i class="fas fa-plus me-2"></i>Ajouter du Stock
        </button>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Type de Produit</th>
                            <th class="text-center">Stock Physique Total</th>
                            <th class="text-center">Vendu / Livré</th>
                            <th class="text-center fw-bold">Disponible (Non Vendu)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($stocks as $stock)
                            <tr>
                                <td class="fw-bold">
                                    <span class="badge bg-{{ $stock->family == 'GM' ? 'primary' : 'success' }}">{{ $stock->family }}</span>
                                    {{ $stock->type }}
                                </td>
                                <td class="text-center">
                                    <span class="editable-stock-total" 
                                          data-family="{{ $stock->family }}" 
                                          data-type="{{ $stock->type }}" 
                                          data-current-total="{{ $stock->total_quantity }}">
                                        {{ $stock->total_quantity }}
                                    </span>
                                    <input type="number" class="form-control form-control-sm d-none stock-input"
                                           value="{{ $stock->total_quantity }}" style="width: 80px; display: inline-block;">
                                    <button class="btn btn-sm btn-primary d-none save-stock-btn" style="margin-left: 5px;">
                                        <i class="fas fa-save"></i>
                                    </button>
                                </td>
                                <td class="text-center">{{ $stock->total_sold }}</td>
                                @php
                                    $available = $stock->total_quantity - $stock->total_sold;
                                @endphp
                                <td class="text-center fw-bold fs-5 {{ $available <= 0 ? 'text-danger' : '' }}">
                                    {{ max(0, $available) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    Aucun stock à afficher.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal d'Ajout de Stock -->
<div class="modal fade" id="addStockModal" tabindex="-1" aria-labelledby="addStockModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStockModalLabel">Ajouter du Stock Brut</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('stock.add') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="family" class="form-label">Famille</label>
                        <select class="form-select" id="family" name="family" required>
                            <option value="" disabled selected>Sélectionner une famille</option>
                            <option value="GM">GM</option>
                            <option value="PM">PM</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="type" class="form-label">Type</label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="" disabled selected>Sélectionner un type</option>
                            <option value="Épicée">Épicée</option>
                            <option value="Pimentée">Pimentée</option>
                            <option value="Nature">Nature</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantité à ajouter</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter au Stock</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Show input and save button on click
        $('.editable-stock-total').on('click', function() {
            $(this).addClass('d-none');
            $(this).siblings('.stock-input').removeClass('d-none').focus();
            $(this).siblings('.save-stock-btn').removeClass('d-none');
        });

        // Hide input and save button if focus is lost and no change
        $('.stock-input').on('blur', function() {
            const $input = $(this);
            const $span = $input.siblings('.editable-stock-total');
            const $button = $input.siblings('.save-stock-btn');

            // Only hide if the value hasn't changed and button is not clicked
            if ($input.val() == $span.data('current-total')) {
                $input.addClass('d-none');
                $button.addClass('d-none');
                $span.removeClass('d-none');
            }
        });

        // Save stock on button click
        $('.save-stock-btn').on('click', function() {
            const $button = $(this);
            const $input = $button.siblings('.stock-input');
            const $span = $button.siblings('.editable-stock-total');

            const family = $span.data('family');
            const type = $span.data('type');
            const newQuantity = $input.val();

            if (newQuantity === '' || newQuantity < 0) {
                alert('Veuillez entrer une quantité valide.');
                return;
            }

            // AJAX call to update stock
            $.ajax({
                url: '{{ route('stock.updateAggregated') }}',
                method: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                    family: family,
                    type: type,
                    new_total_quantity: newQuantity
                },
                success: function(response) {
                    $span.text(response.new_total_quantity);
                    $span.data('current-total', response.new_total_quantity);
                    $input.val(response.new_total_quantity);
                    $input.addClass('d-none');
                    $button.addClass('d-none');
                    $span.removeClass('d-none');
                    alert('Stock mis à jour avec succès!');
                },
                error: function(xhr) {
                    let errorMessage = 'Une erreur inconnue est survenue.';
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMessage = xhr.responseJSON.error;
                    } else if (xhr.responseText) {
                        errorMessage = 'Erreur du serveur: ' + xhr.responseText.substring(0, 100) + '...'; // Truncate long responses
                    }
                    alert('Erreur lors de la mise à jour du stock: ' + errorMessage);
                    // Revert to original state on error
                    $input.val($span.data('current-total'));
                    $input.addClass('d-none');
                    $button.addClass('d-none');
                    $span.removeClass('d-none');
                }
            });
        });
    });
</script>
@endpush