@extends('layouts.ubereats')

@section('title', $etablissement->nometablissement)

@section('css')
    <link rel="stylesheet" href="{{ asset('css/detail-etablissement.css') }}">
@endsection

@section('content')
    <section class="etablissement-detail">


        <div class="etablissement-banner">
            @if($etablissement->imageetablissement && file_exists(public_path('storage/' . $etablissement->imageetablissement)))
                <img src="{{ asset('storage/' . $etablissement->imageetablissement) }}" alt="{{ $etablissement->nometablissement }}">
            @else
                <img src="{{ $etablissement->imageetablissement }}" alt="{{ $etablissement->nometablissement }}">
            @endif
        </div>

        <div class="etablissement-info">
            <h1 class="font-weight-bold text-uppercase">{{ $etablissement->nometablissement }}</h1>

            <div class="etablissement-description">
                <p>{{ $etablissement->description }}</p>
            </div>

            <div class="options">
                <span class="option {{ $etablissement->livraison ? 'active' : '' }}">Livraison</span>
                <span class="option {{ $etablissement->aemporter ? 'active' : '' }}">À emporter</span>
            </div>

            <div class="address-section">
                <p><strong>Adresse:</strong>
                    {{ $etablissement->adresse }}, {{ $etablissement->ville }}
                    ({{ substr($etablissement->codepostal, 0, 2) }})
                </p>
            </div>

            <div class="hours-section">
                <p><strong>Horaires:</strong>

                    @foreach ($horaires as $horaire)
                        <div>{{ $horaire->joursemaine }}:
                            {{ \Carbon\Carbon::parse($horaire->horairesouverture)->format('H\hi') }} -
                            {{ \Carbon\Carbon::parse($horaire->horairesfermeture)->format('H\hi') }}
                        </div>
                    @endforeach
                </p>
            </div>
        </div>
    </section>

    <div class="etablissements-grid my-5">
        @foreach ($produits as $produit)
            <div class="etablissement-card">
                <div class="etablissement-image">
                    <img src="{{ $produit->imageproduit }}" alt="{{ $produit->nomproduit }}">
                </div>
                <div class="etablissement-details">
                    <h5 class="etablissement-name">{{ $produit->nomproduit }}</h5>
                    <h5 class="etablissement-price">{{ $produit->prixproduit }} €</h5>
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
