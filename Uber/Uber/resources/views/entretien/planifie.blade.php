@extends('layouts.app')

@section('title', 'Entretien')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/entretien.blade.css') }}">
@endsection

@section('content')
    <div class="container mt-5">
        <div class="div-planifier p-4">
            <h1 class="text-center mb-4">Entretien Planifié</h1>

            <p class="p-coursier text-center">Votre entretien est planifié avec succès.</p>
            <p class="p-coursier text-center">Voici les détails de l'entretien :</p>

            <strong>Date du rendez-vous :</strong> {{ $entretien->dateentretien->format('d/m/Y H:i') }}<br>
            <strong>Lieu :</strong> À confirmer par le responsable RH.


            <div class="d-inline-flex justify-content-center w-100 my-4">
                {{-- <form method="POST" action="{{ route('coursier.entretien.valider', $entretien->identretien) }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success btn-lg w-100">Confirmer le rendez-vous</button>
                </form> --}}

                <form method="POST" action="{{ route('coursier.entretien.annuler', $entretien->identretien) }}">
                    @csrf
                    <button type="submit" class="btn-entretien">Annuler le rendez-vous</button>
                </form>
                {{-- <a href="{{ route('myaccount') }}" class="btn-entretien text-decoration-none mx-2">Retour à mon compte</a> --}}
            </div>


        </div>
    </div>
@endsection
