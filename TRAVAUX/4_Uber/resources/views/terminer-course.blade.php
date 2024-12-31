@extends('layouts.app')

@section('title', 'Terminer la course')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/detail-course.blade.css') }}">
@endsection

@section('content')
    <section>
        <div class="section-header">
            <h1 class="text-center">Votre course est presque terminée ?</h1>
        </div>

        <div class="d-flex flex-column justify-content-center align-items-center">

            {{-- <p>{{ $course['startAddress'] }}-{{ $course['endAddress'] }}</p> --}}
            {{-- à mettre l'adresse en bien --}}
            <p>Distance parcourue : <strong>{{ $course['distance'] ?? 'Non disponible' }} km</strong></p>
            @php
                $adjusted_time = $course['temps'] ?? 0; // Récupérer les minutes ou 0 si non défini
                $hours = floor($adjusted_time / 60);     // Calcul des heures
                $minutes = $adjusted_time % 60;         // Calcul des minutes restantes
                $formatted_time = sprintf("%2dh%02d minutes", $hours, $minutes); // Formatage en hh:mm
            @endphp
            <p>Durée de la course : <strong>{{ $formatted_time ?? 'Non disponible' }}</strong></p>
            <p>Prix total : <strong>{{ $course['prixcourse'] ?? 'Non spécifié' }}€</strong></p>
        </div>

        <div class="confirmation-message">
            <p class="text-center">Vous êtes satisfait de votre course ?<br> Confirmez ci-dessous pour nous faire part de votre avis !</p>
        </div>

        <div class="d-flex justify-content-center">
            <form method="POST" class="mx-2" action="{{ route('course.addTipRate') }}">
                @csrf
                <input type="hidden" name="idreservation" value="{{ $course['idreservation'] }}">
                <button type="submit" class="btn-annuler">Terminer la Course</button>
            </form>
        </div>
    </section>
@endsection
