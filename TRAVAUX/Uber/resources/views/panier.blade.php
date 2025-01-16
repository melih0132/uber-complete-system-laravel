@extends('layouts.ubereats')

@section('title', 'Panier')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/panier.blade.css') }}">
@endsection

@section('content')
    <div class="container">

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <h1 class="text-center">Votre Panier</h1>
        <div class="cart">
            @if (count($produits) > 0)
                @php
                    $totalPrix = 0;
                @endphp

                @foreach ($produits as $produit)
                    @php
                        $quantite = $quantites[$produit->idproduit] ?? 1;
                        $totalPrix += $produit->prixproduit * $quantite;
                    @endphp
                    <div class="cart-item" data-id="{{ $produit->idproduit }}">
                        <div class="item-info">
                            <div class="item-img">
                                <img src="{{ Str::startsWith($produit->imageproduit, 'http') ? $produit->imageproduit : asset('storage/' . $produit->imageproduit) }}"
                                    alt="{{ $produit->nomproduit }}" class="produit-img" />
                            </div>
                            <div class="item-name">{{ $produit->nomproduit }}</div>
                            <div class="item-price">
                                {{ number_format($produit->prixproduit, 2) }}€ (x{{ $quantite }})
                            </div>
                        </div>
                        <div class="item-controls">
                            <form action="{{ route('panier.mettreAJour', $produit->idproduit) }}" method="POST"
                                class="quantity-form">
                                @csrf
                                @method('PUT')
                                <input type="number" name="quantite" value="{{ $quantite }}" min="1"
                                    max="99" class="quantity-input" onchange="this.form.submit()" />
                            </form>
                            <form action="{{ route('panier.supprimer', $produit->idproduit) }}" method="POST"
                                class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-btn">
                                    <svg viewBox="0 0 24 24">
                                        <path
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach

                <div class="cart-summary">
                    <div class="summary-content">
                        <div class="total">
                            Total : <span id="total-amount">{{ number_format($totalPrix, 2) }}€</span>
                        </div>
                        <div class="actions">
                            <form action="{{ route('panier.vider') }}" method="POST" class="clear-form">
                                @csrf
                                <button type="submit" class="btn-panier">Vider le panier</button>
                            </form>
                            <form action="{{ route('commande.choixLivraison') }}" method="GET" class="order-form">
                                @csrf
                                <button type="submit" class="btn-panier">
                                    Passer la commande
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <div class="empty-cart">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <p>Votre panier est vide</p>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Confirmation avant de vider le panier
            document.querySelectorAll('.clear-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    if (!confirm('Voulez-vous vraiment vider votre panier ?')) {
                        e.preventDefault();
                    }
                });
            });

            // Mise à jour automatique lors du changement de quantité
            document.querySelectorAll('.quantity-select').forEach(select => {
                select.addEventListener('change', function() {
                    this.closest('form').submit();
                });
            });
        });
    </script>
@endsection
