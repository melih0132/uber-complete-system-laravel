@extends('layouts.app')

@section('title', 'Terminer la course')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/detail-course.blade.css') }}">
@endsection

@section('content')
    <section>
        <!-- Messages de succès ou d'erreur -->
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

        <!-- Si aucun coursier n'est encore assigné -->
        @if (is_null($course['idcoursier']))
            <div class="section-header text-center">
                <h1>Nous sommes en attente de Chauffeur !</h1>
                <p class="subtitle">Veuillez patienter.</p>
                <p class="subtitle">N'hésitez pas à rafraîchir la page pour voir si un coursier a accepté votre course.</p>
            </div>
        @else
            <!-- Si un coursier est assigné -->
            <div class="section-header text-center">
                <h1>Un Chauffeur a été trouvé !</h1>
                <p class="subtitle">Merci de valider.</p>
            </div>
            <div class="d-flex flex-column justify-content-center align-items-center mt-4">
                <p>
                    <i class="fas fa-user"></i>
                    Nom chauffeur : <strong>{{ $coursier['nomuser'] }}</strong>
                </p>
                <p>
                    <i class="fas fa-user"></i>
                    Prénom chauffeur : <strong>{{ $coursier['prenomuser'] }}</strong>
                </p>
            </div>
        @endif

        <!-- Actions : annuler ou valider la course -->
        <div class="d-flex justify-content-center mt-3">
            <form method="POST" class="mx-2" action="{{ route('course.cancel') }}" id="cancel-course-form">
                @csrf
                <input type="hidden" name="idreservation" value="{{ $course['idreservation'] }}">
                <button type="submit" class="btn-annuler">
                    <i class="fas fa-times-circle"></i> Annuler la Course
                </button>
            </form>

            @if (!is_null($course['idcoursier']))
                @php
                    $adjusted_time = $course['adjusted_time'] ?? 0;
                    $hours = floor($adjusted_time / 60);
                    $minutes = $adjusted_time % 60;
                    $formatted_time = sprintf('%dh%02d minutes', $hours, $minutes);
                @endphp
                <form method="POST" action="{{ route('course.validate') }}" id="validate-course-form">
                    @csrf
                    <button type="submit" class="btn-valider">
                        <i class="fas fa-check-circle"></i> Valider
                    </button>
                </form>
            @endif
        </div>
    </section>
@endsection

@section('js')
    <script>
        // Prévention de la fermeture de la page sans action
        let isCourseCompleted = false;

        document.getElementById('cancel-course-form').addEventListener('submit', function() {
            isCourseCompleted = true;
        });

        document.getElementById('validate-course-form')?.addEventListener('submit', function() {
            isCourseCompleted = true;
        });

        window.addEventListener('beforeunload', function(event) {
            if (!isCourseCompleted) {
                event.preventDefault();
                event.returnValue = 'Avant de quitter, veuillez terminer ou annuler votre course.';
            }
        });
    </script>
    <script>
        // Configuration du widget Botman
        var botmanWidget = {
            frameEndpoint: '/botman/chat',
            introMessage: "Bienvenue ! Je suis votre assistant Uber. Comment puis-je vous aider ?",
            chatServer: '/botman',
            mainColor: '#000000',
            bubbleBackground: '#FFFFFF',
            bubbleAvatarUrl: '../img/UberLogo.png',
            title: 'Assistant Uber',
            headerTextColor: '#FFFFFF',
            placeholderText: 'Écrivez votre message ici...',
        };
    </script>
    <script src='https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js'></script>
@endsection
