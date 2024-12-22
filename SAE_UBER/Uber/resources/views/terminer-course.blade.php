@extends('layouts.app')

@section('title', 'Terminer la course')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/detail-course.blade.css') }}">
@endsection

@section('content')
    <section>
        <div class="section-header">
            <h2>Votre course est presque terminée !</h2>
        </div>

        <div class="course-details">
            <p>Indiquer que vous êtes arrivé :</p>
            <p><strong>{{ $course['adr_idadresse'] ?? 'Adresse non spécifiée' }}</strong></p>
            {{-- à mettre l'adresse en bien --}}
            <p>Durée de la course : <strong>{{ $course['temps'] ?? 'Non disponible' }} minutes</strong></p>
            <p>Distance parcourue : <strong>{{ $course['distance'] ?? 'Non disponible' }} km</strong></p>
            <p>Prix total : <strong>{{ $course['prixcourse'] ?? 'Non spécifié' }}€</strong></p>
        </div>

        <div class="confirmation-message">
            <p>Vous êtes satisfait de votre course ? Confirmez ci-dessous pour terminer ou annuler.</p>
        </div>

        <div class="d-flex justify-content-center">
            <form method="POST" class="mx-2" action="{{ route('course.addTipRate') }}">
                @csrf
                <input type="hidden" name="idreservation" value="{{ $course['idreservation'] }}">
                <button type="submit" class="btn-annuler">Terminer la Course</button>
            </form>

            <form method="POST" class="mx-2" action="{{ route('course.cancel') }}">
                @csrf
                <input type="hidden" name="course" value="{{ json_encode($course) }}">
                <button type="submit" class="btn-annuler">Annuler</button>
            </form>
        </div>
    </section>
@endsection
