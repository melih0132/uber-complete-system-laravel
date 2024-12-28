@extends('layouts.app')

@section('title', 'Entretien')

@section('content')
    <div class="container">
        <h1>Entretien en Attente</h1>

        <p>Votre entretien est actuellement en attente de validation.</p>

        <p>Vous avez été enregistré dans notre système. Un responsable RH vous contactera bientôt pour planifier un rendez-vous.</p>

        <a href="{{ route('mon-compte') }}" class="btn btn-primary">Retour à mon compte</a>
    </div>
@endsection
