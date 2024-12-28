@extends('layouts.app')

@section('title', 'Terminer la course')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/detail-course.blade.css') }}">
@endsection

@section('content')
    <section>
        <div class="section-header">
            <h2>Votre course est presque terminée ?</h2>
        </div>

        <div class="course-details">

            <p>{{ $course['startAddress'] }}-{{ $course['endAddress'] }}</p>
            {{-- à mettre l'adresse en bien --}}
            @php
                $adjusted_time = $course['temps'] ?? 0; // Récupérer les minutes ou 0 si non défini
                $hours = floor($adjusted_time / 60);     // Calcul des heures
                $minutes = $adjusted_time % 60;         // Calcul des minutes restantes
                $formatted_time = sprintf("%2dh%02d minutes", $hours, $minutes); // Formatage en hh:mm
            @endphp
            <p>Durée de la course : <strong>{{ $formatted_time ?? 'Non disponible' }}</strong></p>
            <p>Distance parcourue : <strong>{{ $course['distance'] ?? 'Non disponible' }} km</strong></p>
            <p>Prix total : <strong>{{ $course['prixcourse'] ?? 'Non spécifié' }}€</strong></p>
        </div>

        <div class="confirmation-message">
            <p>Vous êtes satisfait de votre course ? Confirmez ci-dessous pour nous faire part de votre avis</p>
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
