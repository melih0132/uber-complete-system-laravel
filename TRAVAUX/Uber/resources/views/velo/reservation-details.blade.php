@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Détails de la Réservation</h1>

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">Réserver le Vélo n°{{ $velos['numerovelo'] }}</h5>
                <p><strong>Adresse :</strong> {{ $velos['startAddress'] }}</p>
                <p><strong>Disponibilité :</strong> {{ $velos['disponibilite'] }}</p>
                <p><strong>Date de réservation :</strong> {{ $tripDate }}</p>
                <p><strong>Heure de réservation :</strong> {{ $tripTime }}</p>
                <p><strong>Durée de la réservation :</strong> {{ $durationLabel }}</p>
                <p><strong>Prix estimé :</strong> {{ $price }} €</p>
            </div>
        </div>
        <button type="button" class="btn btn-primary mt-3" onclick="window.history.back();">Retour</button>
        <a href="{{ route('velo.confirmation', ['id' => $velos['veloId']]) }}" class="btn btn-primary mt-3">Confirmer la réservation</a>
    </div>
@endsection
