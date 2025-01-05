@extends('layouts.ubereats')

@section('title', 'Choix de la Carte Bancaire')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/panier.blade.css') }}">
@endsection

@section('content')
    <div class="container">
        <h1>Choisissez une carte bancaire pour le paiement</h1>

        {{-- Affichage des messages de succès ou d'erreur --}}
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

        {{-- Contenu principal --}}
        @if ($cartes->isEmpty())
            <p class="empty-message">Aucune carte enregistrée. Veuillez ajouter une carte pour continuer.</p>
            <a href="{{ route('carte-bancaire.create') }}" class="btn btn-secondary">Ajouter une carte bancaire</a>
        @else
            <form method="POST" action="{{ route('commande.paiementCarte') }}">
                @csrf
                <div class="card-selection mt-4">
                    @foreach ($cartes as $carte)
                        <label>
                            <input type="radio" id="carte_{{ $carte->idcb }}" name="carte_id" value="{{ $carte->idcb }}"
                                required>
                            <span>
                                **** **** **** {{ substr($carte->numerocb, -4) }} - Exp.
                                {{ date('m/Y', strtotime($carte->dateexpirecb)) }}
                            </span>
                        </label>
                    @endforeach
                </div>
                <button type="submit" class="btn btn-primary">Utiliser cette carte</button>
            </form>
            <a href="{{ route('carte-bancaire.create') }}" class="btn btn-secondary mt-4">Ajouter une nouvelle carte
                bancaire</a>
        @endif
    </div>
@endsection
