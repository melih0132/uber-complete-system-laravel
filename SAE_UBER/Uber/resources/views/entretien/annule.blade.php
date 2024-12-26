@extends('layouts.app')

@section('title', 'Entretien')

@section('content')
    <div class="container">
        <h1>Entretien Annulé</h1>

        <p>Votre entretien a été annulé.</p>

        <ul>
            <li><strong>Date de l'entretien :</strong> {{ $entretien->dateentretien->format('d/m/Y H:i') }}</li>
            <li><strong>Statut :</strong> Annulé</li>
        </ul>

        <a href="{{ route('mon-compte') }}" class="btn btn-primary mt-3">Retour à mon compte</a>
    </div>
@endsection
