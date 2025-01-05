@extends('layouts.ubereats')

@section('title', 'Confirmation de commande')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/panier.blade.css') }}">
@endsection

@section('content')

    <div class="container">
        {{-- Notifications de succès ou d'erreur --}}
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

        <h3 class="text-muted text-center mb-4">
            Merci, {{ isset($client->prenomuser) ? $client->prenomuser : 'Cher client' }},
            votre commande a été enregistrée avec succès !
        </h3>

        {{-- Détails de la commande --}}
        <div class="card">
            <div class="card-header">Détails de la commande</div>
            <div class="card-body">
                <ul class="list-group">
                    <li class="list-group-item"><strong>Numéro :</strong> #{{ $commande->idcommande }}</li>
                    <li class="list-group-item"><strong>Adresse :</strong>
                        @if ($adresse)
                            {{ $adresse->libelleadresse }}, {{ $adresse->ville->nomville }}
                            ({{ $adresse->ville->codepostal->codepostal }})
                        @else
                            Non spécifiée
                        @endif
                    </li>
                    <li class="list-group-item"><strong>Total :</strong> {{ number_format($commande->prixcommande, 2) }} €
                    </li>
                    <li class="list-group-item"><strong>Livraison :</strong> {{ $commande->estlivraison ? 'Oui' : 'Non' }}
                    </li>
                    <li class="list-group-item"><strong>Temps estimé :</strong> {{ $commande->tempscommande }} minutes</li>
                </ul>
            </div>
        </div>

        {{-- Produits --}}
        <div class="card">
            <div class="card-header">Produits commandés</div>
            <div class="card-body">
                @if ($produits->isEmpty())
                    <p class="text-muted">Aucun produit dans la commande.</p>
                @else
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Quantité</th>
                                <th>Prix (€)</th>
                                <th>Total (€)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($produits as $produit)
                                <tr>
                                    <td>{{ $produit->nomproduit }}</td>
                                    <td>{{ $produit->pivot->quantite }}</td>
                                    <td>{{ number_format($produit->prixproduit, 2) }}</td>
                                    <td>{{ number_format($produit->prixproduit * $produit->pivot->quantite, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>

        {{-- Bouton retour --}}
        <div class="text-center">
            <a href="{{ route('etablissement.accueilubereats') }}" class="btn btn-primary">
                Retour à l'accueil
            </a>
        </div>
    </div>
@endsection
