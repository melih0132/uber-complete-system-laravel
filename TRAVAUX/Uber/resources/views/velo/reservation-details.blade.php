@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/app.blade.css') }}">
@endsection

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
                <p><strong>Durée choisie :</strong> {{ $formattedDuration }}</p>
                <p><strong>Prix de la réservation :</strong> {{ $priceReservation }} €</p>

            </div>
        </div>

        <button type="button" class="btn-uber mt-3" onclick="window.history.back();">Retour</button>

        <form action="{{ route('velo.confirmation') }}" method="GET">
            @csrf
            <button type="submit" class="btn-uber mt-3">Confirmer la réservation</button>
        </form>
    </div>
@endsection
