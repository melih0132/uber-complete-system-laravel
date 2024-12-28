@extends('layouts.app')

@section('title', 'Entretien')

@section('content')
    <div class="container">
        <h1>Entretien Terminé</h1>

        @if ($entretien->resultat === 'Retenu')
            <p>Félicitations ! Vous avez été retenu pour le poste.</p>
            @if ($entretien->rdvlogistiquedate && $entretien->rdvlogistiquelieu)
                <p>Un rendez-vous logistique a été programmé pour finaliser votre intégration :</p>
                <ul>
                    <li><strong>Date du rendez-vous :</strong> {{ \Carbon\Carbon::parse($entretien->rdvlogistiquedate)->format('d/m/Y H:i') }}</li>
                    <li><strong>Lieu :</strong> {{ $entretien->rdvlogistiquelieu }}</li>
                </ul>
                <p>Veuillez vous rendre à l'adresse indiquée à la date et à l'heure spécifiées pour compléter votre inscription et recevoir votre équipement.</p>
            @else
                <p>Vous serez contacté sous peu pour planifier un rendez-vous logistique.</p>
            @endif
        @elseif($entretien->resultat === 'Rejeté')
            <p>Désolé, votre entretien n'a pas abouti. Nous vous remercions pour votre temps et l'intérêt que vous avez porté à notre entreprise.</p>
        @else
            <p>Votre entretien a été terminé avec succès. Vous serez informé de la décision finale après l'examen de votre entretien.</p>
        @endif

        <ul>
            <li><strong>Date de l'entretien :</strong> {{ \Carbon\Carbon::parse($entretien->dateentretien)->format('d/m/Y H:i') }}</li>
            <li><strong>Statut :</strong> {{ $entretien->status }}</li>
            @if (!is_null($entretien->resultat))
                <li><strong>Résultat :</strong> {{ $entretien->resultat }}</li>
            @endif
        </ul>

        <a href="{{ route('mon-compte') }}" class="btn btn-primary mt-3">Retour à mon compte</a>
    </div>
@endsection
