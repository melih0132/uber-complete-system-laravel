@extends('layouts.ubereats')

@section('title', 'Mes Commandes')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/myaccount.blade.css') }}">
@endsection

@section('content')
    <div class="container">
        <h1>Mes Commandes</h1>

        <!-- Affichage des messages de succès ou d'erreur -->
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

        <!-- Tableau des commandes -->
        <table class="table table-striped mt-5">
            <thead class="table-uber">
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Prix</th>
                    <th>Statut</th>
                    <th>Détails</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($commandes as $commande)
                    <tr>
                        <td>{{ $commande->idcommande }}</td>
                        <td>{{ \Carbon\Carbon::parse($commande->heurecreation)->format('d/m/Y H:i') }}</td>
                        <td>{{ number_format($commande->prixcommande, 2) }} €</td>
                        <td>{{ $commande->statutcommande }}</td>
                        <td>
                            <!-- Bouton pour afficher les détails -->
                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                                data-bs-target="#commandeDetailModal{{ $commande->idcommande }}">
                                Voir détails
                            </button>

                            <!-- Modal pour les détails de la commande -->
                            <div class="modal fade" id="commandeDetailModal{{ $commande->idcommande }}" tabindex="-1"
                                aria-labelledby="commandeDetailModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="commandeDetailModalLabel">
                                                Détails de la commande #{{ $commande->idcommande }}
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>ID Commande :</strong> {{ $commande->idcommande }}</p>
                                            <p><strong>Date de création :</strong>
                                                {{ \Carbon\Carbon::parse($commande->heurecreation)->format('d/m/Y H:i') }}
                                            </p>
                                            <p><strong>Prix :</strong> {{ number_format($commande->prixcommande, 2) }} €</p>
                                            <p><strong>Statut :</strong> {{ $commande->statutcommande }}</p>
                                            <p><strong>Livraison :</strong> {{ $commande->estlivraison ? 'Oui' : 'Non' }}</p>
                                            @if ($commande->adresse)
                                                <p><strong>Adresse de livraison :</strong>
                                                    {{ $commande->adresse->libelleadresse }}</p>
                                            @endif
                                            <p><strong>Produits :</strong></p>
                                            <ul>
                                                @foreach ($commande->panier->produits as $produit)
                                                    <li>
                                                        {{ $produit->nomproduit }} x{{ $produit->pivot->quantite ?? 1 }}
                                                        ({{ number_format($produit->prixproduit, 2) }} €)
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                Fermer
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if ($commande->statutcommande === 'Livrée')
                                @if (!$commande->refus_demandee)
                                    <form method="POST"
                                        action="{{ route('commande.informerRefus', $commande->idcommande) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-warning btn-sm">Informer du refus</button>
                                    </form>
                                @else
                                    <button type="button" class="btn btn-secondary btn-sm" disabled>Refus demandé</button>
                                @endif
                            @else
                                <span class="text-muted">Aucune action</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Aucune commande trouvée.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination des commandes -->
        <div class="d-flex justify-content-center mt-4">
            {{ $commandes->appends(request()->except('page'))->onEachSide(1)->links('pagination::bootstrap-4') }}
        </div>
    </div>
@endsection
