@extends('layouts.app')

@section('title', 'Détails de la réservation')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/detail-course.blade.css') }}">
@endsection

@section('content')
    <section class="py-5">

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

        <div class="container">
            <h1 class="text-center mb-4 fw-bold">Détails de la réservation</h1>
            <div class="details-wrapper row gx-5 gy-4 align-items-center">
                <div class="col-lg-6 text-center">
                    <img alt="Image de la prestation" class="img-fluid rounded"
                        src="{{ asset('img/' . $course['imageprestation']) }}" loading="lazy">
                </div>
                <div class="col-lg-6">
                    <ul class="list-details list-unstyled">
                        <li class="detail-item">
                            <strong>Adresse de départ :</strong>
                            <span>{{ $course['startAddress'] }}</span>
                        </li>
                        <li class="detail-item">
                            <strong>Adresse d'arrivée :</strong>
                            <span>{{ $course['endAddress'] }}</span>
                        </li>
                        <li class="detail-item">
                            <strong>Date de la course :</strong>
                            <span>{{ $course['tripDate'] }}</span>
                        </li>
                        <li class="detail-item">
                            <strong>Heure de la course :</strong>
                            <span>{{ $course['tripTime'] }}</span>
                        </li>
                        <li class="detail-item">
                            <strong>Nom du client :</strong>
                            <span>{{ $client->nomuser }}</span>
                        </li>
                        <li class="detail-item">
                            <strong>Prix de la course :</strong>
                            <span>{{ $course['calculated_price'] }} €</span>
                        </li>
                        <li class="detail-item">
                            <strong>Distance :</strong>
                            <span>{{ $course['distance'] }} km</span>
                        </li>
                        <li class="detail-item">
                            <strong>Temps estimé :</strong>
                            <span>
                                @php
                                    $adjusted_time = $course['adjusted_time'] ?? 0;
                                    $hours = floor($adjusted_time / 60);
                                    $minutes = $adjusted_time % 60;
                                    $formatted_time = sprintf('%dh%02d minutes', $hours, $minutes);
                                @endphp
                                {{ $formatted_time }}
                            </span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="button-container mt-4 d-flex justify-content-center gap-3">
                <form method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler ?')" class="mx-2">
                    @csrf
                    <button type="button" class="btn-annuler"
                        onclick="window.history.back() || window.location.href='{{ route('accueil') }}'; return false;">
                        Annuler
                    </button>
                </form>
                <form method="POST" action="{{ route('course.searchDriver') }}">
                    @csrf
                    <input type="hidden" name="course" value="{{ json_encode($course) }}">
                    <button type="submit" class="btn-valider">Valider</button>
                </form>
            </div>
        </div>
    </section>
    <script>
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
