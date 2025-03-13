@extends('layouts.app')

@section('title', 'Entretien')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/entretien.blade.css') }}">
@endsection

@section('content')
    <div class="container mt-5">
        <div class="div-planifier p-4">
            <h1 class="text-center">Entretien Terminé</h1>

            @if ($entretien->resultat === 'Retenu')
                <p class="p-coursier text-center">Félicitations ! Vous avez été retenu pour le poste.</p>
                @if ($entretien->rdvlogistiquedate && $entretien->rdvlogistiquelieu)
                    <p class="p-coursier text-center">Un rendez-vous avec le service logistique a été programmé afin de
                        finaliser votre intégration.</p>
                    <p class="p-coursier">Voici les détails de l'entretien :</p>
                    <div class="mx-3">
                        <strong>Date du rendez-vous :</strong>
                        {{ \Carbon\Carbon::parse($entretien->rdvlogistiquedate)->format('d/m/Y H:i') }}<br>
                        <strong>Lieu :</strong> {{ $entretien->rdvlogistiquelieu }}
                    </div>
                    <p class="p-coursier text-center my-4">Veuillez vous rendre à l'adresse indiquée à la date et à l'heure
                        spécifiées pour compléter votre inscription et recevoir votre équipement.</p>
                @else
                    <p>Vous serez contacté sous peu pour planifier un rendez-vous logistique.</p>
                @endif
            @elseif($entretien->resultat === 'Rejeté')
                <p class="p-coursier text-center">Désolé, votre entretien n'a pas abouti. Nous vous remercions pour votre
                    temps et l'intérêt que vous avez
                    porté à notre entreprise.</p>
            @else
                <p class="p-coursier text-center">Votre entretien a été effectué avec succès.</p>
                <p class="p-coursier text-center"> Vous serez informé de la décision finale après l'examen de votre
                    entretien.</p>
            @endif

            {{-- <ul>
                <li><strong>Date de l'entretien :</strong>
                    {{ \Carbon\Carbon::parse($entretien->dateentretien)->format('d/m/Y H:i') }}</li>
                <li><strong>Statut :</strong> {{ $entretien->status }}</li>
                @if (!is_null($entretien->resultat))
                    <li><strong>Résultat :</strong> {{ $entretien->resultat }}</li>
                @endif
            </ul> --}}
            <div class="d-flex justify-content-center">
                <a href="{{ route('myaccount') }}" class="btn-entretien text-decoration-none">Retour à mon compte</a>
            </div>
        </div>
    </div>
@endsection
