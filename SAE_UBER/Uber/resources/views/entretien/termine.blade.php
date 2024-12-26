@extends('layouts.app')

@section('title', 'Entretien')

@section('content')
    <div class="container">
        <h1>Entretien Terminé</h1>

        @if($entretien->resultat === 'Retenu')
            <p>Félicitations ! Vous avez été retenu pour le poste. Vous avez désormais accès à la partie Conduire.</p>
        @elseif($entretien->resultat === 'Rejeté')
            <p>Désolé, votre entretien n'a pas abouti. Nous vous remercions pour votre temps.</p>
        @else
            <p>Votre entretien a été terminé avec succès. Vous serez informé de la décision finale après l'examen de votre entretien.</p>
        @endif

        <ul>
            <li><strong>Date de l'entretien :</strong> {{ $entretien->dateentretien->format('d/m/Y H:i') }}</li>
            <li><strong>Statut :</strong> Terminée</li>
            @if(!is_null($entretien->resultat))
                <li><strong>Résultat :</strong> {{ $entretien->resultat }}</li>
            @endif
        </ul>

        <a href="{{ route('mon-compte') }}" class="btn btn-primary mt-3">Retour à mon compte</a>
    </div>
@endsection
