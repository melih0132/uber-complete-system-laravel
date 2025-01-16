@extends('layouts.ubereats')

@section('title', 'Confirmation de réservation de vélo')

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

        <h3 class="text-muted text-center mb-4">
            Merci {{ $client->genreuser }} {{ $client->nomuser }} {{ $client->prenomuser }},
            votre réservation a été enregistrée avec succès !
        </h3>
        <div>
            <h3 class="text-muted text-center mt-4">
                Merci de noter le numéro de réservation pour votre référence.
            </h3>
        </div>

        <div class="card mt-4">
            <div class="card-header">Détails de la réservation</div>
            <div class="card-body">
                <ul class="list-group">
                    <li class="list-group-item"><strong>Numéro de réservation :</strong> #{{ $reservation->idreservation }}</li>
                    <li class="list-group-item">
                        <strong>Vélo réservé :</strong> Vélo n°{{ $velo->numerovelo }}
                    </li>
                    <li class="list-group-item"><strong>Adresse de retrait :</strong> {{ $velo->adresse->libelleadresse ?? 'Adresse non spécifiée' }}</li>
                    <li class="list-group-item"><strong>Disponibilité :</strong> {{ $velo->estdisponible ? 'Disponible' : 'Réservé' }}</li>
                    <li class="list-group-item"><strong>Date de réservation :</strong> {{ $tripDate }}</li>
                    <li class="list-group-item"><strong>Heure de réservation :</strong> {{ $tripTime }}</li>
                    <li class="list-group-item"><strong>Durée de réservation :</strong> {{ $durationText }}</li>
                </ul>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">Détails du paiement</div>
            <div class="card-body">
                <ul class="list-group">
                    <li class="list-group-item"><strong>Prix total :</strong> {{ $price }} €</li>
                    @if ($velo->estdisponible)
                        <li class="list-group-item"><strong>Frais supplémentaires :</strong> Aucun</li>
                    @else
                        <li class="list-group-item"><strong>Frais supplémentaires :</strong> Frais pour vélo réservé non disponible</li>
                    @endif
                </ul>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('velo.index') }}" class="btn-panier text-decoration-none mb-4">
                Voir mes réservations
            </a>
        </div>

        <div class="text-center mt-4">
            <form action="{{ route('velo.paiement') }}" method="GET">
                @csrf
                <input type="hidden" name="reservation_id" value="{{ session('reservation')['idreservation'] }}">
                <input type="hidden" name="price" value="{{ $price }}">
                <button type="submit" class="btn-uber mt-3">Confirmer et procéder au paiement</button>
            </form>
        </div>
    </div>
@endsection
