@extends('layouts.app')

@section('title', 'Entretien')

@section('content')
    <div class="container mt-5">
        <div class="card shadow-lg p-4">
            <h1 class="text-center text-primary mb-4">Entretien Planifié</h1>

            <p class="lead text-muted">Votre entretien est planifié avec succès. Voici les détails de l'entretien :</p>

            <ul class="list-group mb-4">
                <li class="list-group-item"><strong>Date du rendez-vous :</strong> {{ $entretien->dateentretien->format('d/m/Y H:i') }}</li>
                <li class="list-group-item"><strong>Lieu :</strong> À confirmer par le responsable RH.</li>
            </ul>

            <div class="d-flex justify-content-between mb-4">
                <form method="POST" action="{{ route('coursier.entretien.valider', $entretien->identretien) }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success btn-lg w-100">Confirmer le rendez-vous</button>
                </form>

                <form method="POST" action="{{ route('coursier.entretien.annuler', $entretien->identretien) }}" class="d-inline ms-3">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-lg w-100">Annuler le rendez-vous</button>
                </form>
            </div>

            <a href="{{ route('mon-compte') }}" class="btn btn-primary btn-lg w-100 mt-3">Retour à mon compte</a>
        </div>
    </div>
@endsection
