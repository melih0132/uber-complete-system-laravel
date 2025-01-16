@extends('layouts.ubereats')

@section('title', 'Livraison en cours')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/detail-course.blade.css') }}">
@endsection

@section('content')
    <div class="container py-5">
        <h1 class="text-center mb-4">Livraison en cours</h1>

        @if (!$livraison)
            <div class="alert alert-light text-center border">
                <h4 class="fw-bold">Aucune livraison en cours</h4>
                <p>Vous n'avez pas de livraison assignée pour le moment.</p>
            </div>
        @else
            <div class="card shadow border-0">
                <div class="card-body">
                    <h5 class="card-title mb-4 fw-bold">Commande #{{ $livraison->idcommande }}</h5>

                    <div class="mb-3">
                        <span class="fw-bold d-block">Client :</span>
                        <span>
                            {{ $livraison->panier->client->genreuser }}
                            {{ $livraison->panier->client->nomuser }}
                            {{ $livraison->panier->client->prenomuser }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <span class="fw-bold d-block">Adresse de livraison :</span>
                        <span>{{ $livraison->adresseDestination->libelleadresse }}</span>
                    </div>

                    <div class="mb-3">
                        <span class="fw-bold d-block">Ville :</span>
                        <span>{{ $livraison->adresseDestination->ville->nomville }}</span>
                    </div>

                    <div class="mb-3">
                        <span class="fw-bold d-block">Code postal :</span>
                        <span>{{ $livraison->adresseDestination->ville->codepostal->codepostal }}</span>
                    </div>

                    <div class="mb-3">
                        <span class="fw-bold d-block">Prix :</span>
                        <span class="text-success">{{ number_format($livraison->prixcommande, 2) }} €</span>
                    </div>

                    <div class="mb-3">
                        <span class="fw-bold d-block">Temps estimé :</span>
                        <span>{{ $livraison->tempscommande }} minutes</span>
                    </div>

                    <div class="mb-3">
                        <span class="fw-bold d-block">Heure de commande :</span>
                        <span>{{ \Carbon\Carbon::parse($livraison->heurecommande)->translatedFormat('d F Y à H:i') }}</span>
                    </div>

                    <form action="{{ route('livreur.livraisons.marquerLivree', $livraison->idcommande) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn-valider">
                            Marquer comme livrée
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
@endsection
