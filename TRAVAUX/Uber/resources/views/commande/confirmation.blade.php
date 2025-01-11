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

        {{-- Message de remerciement --}}
        <h3 class="text-muted text-center mb-4">
            Merci {{ $client->genreuser }} {{ $client->nomuser }} {{ $client->prenomuser }},
            votre commande a été enregistrée avec succès !
        </h3>
        <div>
            <h3 class="text-muted text-center mt-4">
                Merci de noter le numéro de commande qui servira au livreur !
            </h3>
        </div>

        {{-- Détails de la commande --}}
        <div class="card mt-4">
            <div class="card-header">Détails de la commande</div>
            <div class="card-body">
                <ul class="list-group">
                    <li class="list-group-item"><strong>Numéro :</strong> #{{ $commande->idcommande }}</li>
                    <li class="list-group-item">
                        <strong>Type Commande :</strong>
                        @if ($commande->estlivraison)
                            Livraison à l'adresse :
                            {{ $commande->adresse->libelleadresse ?? 'Adresse non spécifiée' }})
                        @else
                            Retrait
                        @endif
                    </li>
                    <li class="list-group-item"><strong>Temps estimé :</strong> {{ $commande->tempscommande }} minutes</li>
                </ul>
            </div>
        </div>

        {{-- Produits commandés --}}
        <div class="card mt-4">
            <div class="card-header">Produits commandés</div>
            <div class="card-body">
                @if ($produits->isEmpty())
                    <p class="text-muted">Aucun produit dans la commande.</p>
                @else
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Établissement</th>
                                <th>Quantité</th>
                                <th>Total (€)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = 0; @endphp
                            @foreach ($produits as $produit)
                                <tr>
                                    <td>{{ $produit->nomproduit }}</td>
                                    <td>
                                        {{ $produit->etablissements->firstWhere('idetablissement', $produit->pivot->idetablissement)->nometablissement ?? 'Non spécifié' }}
                                    </td>
                                    <td>{{ $produit->pivot->quantite }}</td>
                                    <td>{{ number_format($produit->prixproduit * $produit->pivot->quantite, 2) }}</td>
                                </tr>
                                @php $total += $produit->prixproduit * $produit->pivot->quantite; @endphp
                            @endforeach
                            @if ($commande->estlivraison)
                                <tr>
                                    <td colspan="3">Frais de Livraison</td>
                                    <td colspan="2">4,60</td>
                                </tr>
                            @endif
                            <tr>
                                <td colspan="3"><strong>Total</strong></td>
                                <td colspan="2">
                                    {{ $commande->estlivraison ? number_format($total + 4.6, 2) : number_format($total, 2) }}
                                    €
                                </td>
                            </tr>
                        </tbody>
                    </table>
                @endif
            </div>
        </div>

        {{-- Bouton retour --}}
        <div class="text-center mt-4">
            <a href="{{ route('panier.vider') }}" class="btn-panier text-decoration-none mb-4">
                Retour à l'accueil
            </a>
        </div>
    </div>
@endsection
