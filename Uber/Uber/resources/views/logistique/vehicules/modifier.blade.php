@extends('layouts.app')

@section('title', 'Aménagements')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/entretien.blade.css') }}">
@endsection

@section('content')
    <div class="container mt-5">
        <div class="div-logistique">
            <h1 class="text-center">Demande d'aménagement(s)</h1>

            <div class="mb-4">
                <h3>Informations du coursier</h3>
                <p><strong>ID :</strong> {{ $coursier->idcoursier }}</p>
                <p><strong>Nom :</strong> {{ $coursier->nomuser }}</p>

                <h3>Informations du Véhicule</h3>
                <p><strong>Modèle :</strong> {{ $vehicule->modele ?? 'Non attribué' }}</p>
                <p><strong>Marque :</strong> {{ $vehicule->marque ?? 'Non attribuée' }}</p>
                <p><strong>Immatriculation :</strong> {{ $vehicule->immatriculation ?? 'Non spécifiée' }}</p>
            </div>

            <form action="{{ route('logistique.vehicules.modifier', $coursier->idcoursier) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="demandemodification">Aménagement(s) nécessaire(s) pour le véhicule :</label>
                    <textarea name="demandemodification" id="demandemodification" rows="5" class="form-control"
                        placeholder="Décrivez les modifications nécessaires" required>{{ old('demandemodification') }}</textarea>
                </div>

                <button type="submit" class="btn-entretien text-decoration-none mt-3">Envoyer la demande</button>
            </form>

            <a href="{{ route('logistique.vehicules') }}" class="btn-entretien text-decoration-none mt-3">Retour</a>
        </div>
    </div>
@endsection
