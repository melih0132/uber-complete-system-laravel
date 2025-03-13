@extends('layouts.app')

@section('title', 'Terminer la course')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/detail-course.blade.css') }}">
@endsection

@section('content')
    <section>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="section-header text-center">
            <h1>Votre trajet touche à sa fin !</h1>
            <p class="subtitle">Merci de confirmer votre fin de course ci-dessous.</p>
            <p class="subtitle">Remarque : il faut que votre coursier valide la fin de la course avant que vous puissiez
                valider.</p>
        </div>

        <div class="d-flex flex-column justify-content-center align-items-center mt-4">
            <p>
                <i class="fas fa-route"></i>
                Distance parcourue : <strong>{{ $distance ?? 'Non disponible' }} km</strong>
            </p>
            @php
                $adjusted_time = $temps ?? 0;
                $hours = floor($adjusted_time / 60);
                $minutes = $adjusted_time % 60;
                $formatted_time = sprintf('%2dh%02d minutes', $hours, $minutes);
            @endphp
            <p>
                <i class="fas fa-clock"></i>
                Durée de la course : <strong>{{ $formatted_time ?? 'Non disponible' }}</strong>
            </p>
            <p>
                <i class="fas fa-euro-sign"></i>
                Prix total : <strong>{{ $prixcourse ?? 'Non spécifié' }} €</strong>
            </p>
        </div>

        <div class="d-flex justify-content-center mt-3">

            <form method="POST" class="mx-2" action="{{ route('course.addTipRate') }}" id="complete-course-form">
                @csrf
                <input type="hidden" name="idreservation" value="{{ $idreservation }}">
                <button type="submit" class="btn-valider">
                    <i class="fas fa-check-circle"></i> Terminer la Course
                </button>
            </form>
        </div>
    </section>
@endsection

@section('js')
    <script>
        let isCourseCompleted = false;

        document.getElementById('complete-course-form').addEventListener('submit', function() {
            isCourseCompleted = true;
        });

        window.addEventListener('beforeunload', function(event) {
            if (!isCourseCompleted) {
                event.preventDefault();
                event.returnValue = 'Avant de quitter, veuillez terminer ou annuler votre course.';
            }
        });
    </script>
@endsection
