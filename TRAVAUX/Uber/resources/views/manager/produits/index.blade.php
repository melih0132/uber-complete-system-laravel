@extends('layouts.app')

@section('title', 'Tous les produits')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/produit.css') }}">
@endsection

@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-4">Tous les Produits</h1>

        @if ($produits->isEmpty())
            <div class="alert alert-info text-center">
                Aucun produit trouvé. Ajoutez un produit pour commencer !
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Nom</th>
                            <th>Prix</th>
                            <th>Description</th>
                            <th>Établissement</th>
                            <th>Catégorie</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($produits as $produit)
                            <tr>
                                <td>{{ $produit->nomproduit }}</td>
                                <td>{{ number_format($produit->prixproduit, 2, ',', ' ') }} €</td>
                                <td>{{ $produit->description }}</td>
                                <td>
                                    @foreach ($produit->etablissements as $etablissement)
                                        <span class="badge bg-info">{{ $etablissement->nometablissement }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($produit->categories as $categorie)
                                        <span class="badge bg-secondary">{{ $categorie->nomcategorie }}</span>
                                    @endforeach
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <div class="text-end mb-3">
            <a href="{{ route('manager.produits.create') }}" class="btn btn-primary">
                Ajouter un Produit
            </a>
        </div>
    </div>
@endsection
