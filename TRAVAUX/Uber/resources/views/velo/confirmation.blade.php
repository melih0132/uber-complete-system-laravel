@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Confirmation de Réservation</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card mt-4">
            <div>
                <h5 class="card-title">Réserver le Vélo n°{{ $velo['numerovelo'] }}</h5>
                <p><strong>Adresse :</strong> {{ $velo['startAddress'] }}</p>
                <p><strong>Disponibilité :</strong> {{ $velo['disponibilite'] }}</p>
                <p><strong>Date de réservation :</strong> {{ $tripDate }}</p>
                <p><strong>Heure de réservation :</strong> {{ $tripTime }}</p>
                <p><strong>Durée de la réservation :</strong> {{-- {{ $durationLabel }} --}}</p>
                <p><strong>Prix estimé :</strong>{{--  {{ $price }} --}} €</p>
            </div>

        </div>

        <a href="{{ route('velo.paiement') }}" class="btn btn-primary mt-4">Passer au paiement</a>
    </div>
@endsection
