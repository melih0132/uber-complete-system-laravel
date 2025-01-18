@extends('layouts.app')

@section('title', 'Détails de la réservation')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/detail-course.blade.css') }}">
@endsection

@section('content')
    <section>
        <div class="container">
            <h1 class="mt-4">Détails de la réservation :</h1>

            <div class="details mt-4">
                <ul class="liste">
                    <li><strong>Type :</strong> {{ $type == 'course' ? 'Course' : 'Livraison' }}</li>
                    <li><strong>Client :</strong> {{ $taskDetails->genreuser }} {{ $taskDetails->nomuser }}
                        {{ $taskDetails->prenomuser }}</li>
                    @if ($type == 'course')
                        <li><strong>Adresse de départ :</strong> {{ $taskDetails->libelle_idadresse ?? 'Non spécifiée' }}
                        </li>
                        <li><strong>Adresse de destination :</strong>
                            {{ $taskDetails->libelle_adr_idadresse ?? 'Non spécifiée' }}</li>
                    @endif
                    <li><strong>Ville :</strong> {{ $taskDetails->nomville ?? 'Non spécifiée' }}</li>
                    <li><strong>Prix estimé :</strong>
                        {{ $type == 'course' ? $taskDetails->prixcourse : $taskDetails->prixcommande }} €</li>
                    <li><strong>Distance :</strong> {{ $taskDetails->distance ?? 'Non spécifiée' }} km</li>
                    <li><strong>Temps estimé :</strong>
                        {{ $type == 'course' ? $taskDetails->temps : $taskDetails->tempscommande }} minutes</li>
                    <li><strong>Statut :</strong>
                        {{ $type == 'course' ? $taskDetails->statutcourse : $taskDetails->statutcommande }}</li>
                </ul>
            </div>

            <div class="button-container mt-4">
                <form method="POST"
                    action="{{ route($type == 'course' ? 'coursier.courses.finish' : 'coursier.livraisons.finish', ['idreservation' => $id]) }}"
                    class="mt-2">
                    @csrf
                    <button type="submit" class="btn-valider">Fin de la tâche</button>
                </form>
            </div>
        </div>
    </section>
@endsection
