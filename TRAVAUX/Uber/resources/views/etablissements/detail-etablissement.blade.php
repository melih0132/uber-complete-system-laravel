@extends('layouts.ubereats')

@section('title', $etablissement->nometablissement)

@section('css')
    <link rel="stylesheet" href="{{ asset('css/detail-etablissement.blade.css') }}">
@endsection

@section('content')
    <section class="etablissement-detail">
        <div class="etablissement-banner">
            @if ($etablissement->imageetablissement && file_exists(public_path('storage/' . $etablissement->imageetablissement)))
                <img src="{{ asset('storage/' . $etablissement->imageetablissement) }}"
                    alt="{{ $etablissement->nometablissement }}">
            @else
                <img src="{{ $etablissement->imageetablissement }}" alt="{{ $etablissement->nometablissement }}">
            @endif
        </div>

        <div class="main d-flex">
            <div class="main-info">
                <h1 class="font-weight-bold text-uppercase">{{ $etablissement->nometablissement }} -
                    {{ $etablissement->ville }}</h1>

                <div class="categories-section">
                    <p class="categories">
                        @foreach ($categoriesPrestations->take(3) as $index => $categorie)
                            {{ $categorie->libellecategorieprestation }}
                            @if ($index < 2 && !$loop->last)
                                <!-- Ajoute "•" seulement pour les deux premiers -->
                                •
                            @endif
                        @endforeach
                    </p>
                </div>

                <div class="etablissement-description">
                    <p>{{ $etablissement->description }}</p>
                </div>

                <div class="address-section">
                    <p>{{ $etablissement->adresse }}, {{ $etablissement->ville }}
                        ({{ substr($etablissement->codepostal, 0, 2) }})</p>
                </div>
            </div>

            <div class="etablissement-info">
                <div class="options-container">
                    <div class="options">
                        <span class="option {{ $etablissement->livraison ? 'active' : '' }}">Livraison</span>
                        <span class="option {{ $etablissement->aemporter ? 'active' : '' }}">À emporter</span>
                    </div>
                </div>
                <div class="hours-section">
                    <ul class="hours-list">
                        @foreach ($groupedHoraires as $horaire => $jours)
                            <li class="hours-item">
                                <span class="days">{{ implode(', ', $jours) }}</span>
                                <span class="time">
                                    @if ($horaire === 'Fermé')
                                        <span class="closed">Fermé</span>
                                    @else
                                        {{ $horaire }}
                                    @endif
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <div class="products-grid my-5">
        @foreach ($produits as $produit)
            <div class="product-card">
                <div class="product-image">
                    <img src="{{ $produit->imageproduit }}" alt="{{ $produit->nomproduit }}">
                </div>
                <div class="product-details">
                    <h5 class="product-name">{{ $produit->nomproduit }}</h5>
                    <h5 class="product-price">{{ $produit->prixproduit }} €</h5>
                    <form method="POST" action="{{ route('panier.ajouter') }}">
                        @csrf
                        <input name="product" value="{{ $produit->idproduit }}" type="hidden">
                        <button type="submit" class="btn-panier">Ajouter au panier</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@endsection
