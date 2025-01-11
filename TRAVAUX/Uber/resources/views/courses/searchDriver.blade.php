@extends('layouts.app')

@section('title', 'Terminer la course')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/detail-course.blade.css') }}">
@endsection

@section('content')
    <section>
        @if ($course['idcoursier'] == null)
            <div class="section-header text-center">
                <h1>Nous sommes en attente de Chauffeur !</h1>
                <p class="subtitle">Veuillez patientez.</p>
            </div>
        @else
            <div class="section-header text-center">
                <h1>Un Chauffeur à été trouvé !</h1>
                <p class="subtitle">Merci de valider.</p>
            </div>
            <div class="d-flex flex-column justify-content-center align-items-center mt-4">
                <p>
                    <i class="fas fa-route"></i>
                    Nom chauffeur : <strong>{{ $coursier['nomuser'] }}</strong>
                </p>
                <p>
                    <i class="fas fa-route"></i>
                    Prénom chauffeur : <strong>{{ $coursier['prenomuser'] }}</strong>
                </p>
        @endif
        <div class="d-flex justify-content-center mt-3">
            <form method="POST" class="mx-2" action="{{ route('course.cancel') }}" id="cancel-course-form">
                @csrf
                <input type="hidden" name="idreservation" value="{{ $course['idreservation'] }}">
                <button type="submit" class="btn-annuler">
                    <i class="fas fa-times-circle"></i> Annuler la Course
                </button>
            </form>
            @if ($course['idcoursier'] == !null)
                @php
                    $adjusted_time = $course['adjusted_time'] ?? 0;
                    $hours = floor($adjusted_time / 60);
                    $minutes = $adjusted_time % 60;
                    $formatted_time = sprintf('%dh%02d minutes', $hours, $minutes);
                @endphp
                <form method="POST" action="{{ route('course.validate') }}">
                    @csrf
                    <button type="submit" class="btn-valider">Valider</button>
                </form>
            @endif
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
