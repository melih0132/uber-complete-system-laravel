@extends('layouts.app')

@section('title', 'Coursier')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/coursier.blade.css') }}">
@endsection

@section('content')
    <section>
        <div class="container">
            <h1 class="mt-5">{{ $type === 'courses' ? 'Courses en attente :' : 'Livraisons en attente :' }}</h1>
            <ul class="liste my-5">
                @foreach ($tasks as $task)
                    <div class="item-course mt-3" data-id="{{ $task->idreservation ?? $task->idcommande }}">
                        <ul>
                            <li class="task-items">
                                N° {{ $type === 'courses' ? 'réservation' : 'commande' }} :
                                <strong>{{ $task->idreservation ?? $task->idcommande }}</strong>
                            </li>
                            <li class="task-items">
                                Client : <strong>{{ $task->genreuser }} {{ $task->nomuser }}
                                    {{ $task->prenomuser }}</strong>
                            </li>
                            @if ($type === 'courses')
                                <li class="task-items">
                                    Date de la course :
                                    @php
                                        $formattedDate = $task->datecourse
                                            ? \Carbon\Carbon::parse($task->datecourse)->isoFormat('D MMMM YYYY')
                                            : 'Non spécifiée';
                                    @endphp
                                    <strong>{{ $formattedDate }}</strong>
                                </li>
                                <li class="task-items">
                                    Heure de départ :
                                    <strong>{{ $task->heurecourse ? \Carbon\Carbon::parse($task->heurecourse)->format('H:i') : 'Non spécifiée' }}</strong>
                                </li>
                            @else
                                <li class="task-items">
                                    Temps estimé de livraison :
                                    <strong>{{ $task->tempscommande ? \Carbon\Carbon::parse($task->tempscommande)->format('H:i') : 'Non spécifiée' }}</strong>
                                </li>
                            @endif
                            <li class="task-items">
                                Adresse de départ : <strong>{{ $task->libelle_idadresse ?? 'Non spécifiée' }},
                                    {{ $task->nomville ?? 'Non spécifiée' }}</strong>
                            </li>
                            <li class="task-items">
                                Adresse de destination : <strong>{{ $task->libelle_adr_idadresse ?? 'Non spécifiée' }},
                                    {{ $task->nomville ?? 'Non spécifiée' }}</strong>
                            </li>
                            <li class="task-items">
                                Prix estimé : <strong>{{ $task->prixcourse ?? $task->prixcommande }}</strong> €
                            </li>
                            @if ($type === 'courses')
                                <li class="task-items">
                                    Distance : <strong>{{ $task->distance }}</strong> km
                                </li>
                                <li class="task-items">
                                    Temps estimé : <strong>{{ $task->temps }}</strong> minutes
                                </li>
                            @endif
                            <div class="d-inline-flex pt-3">
                                <form method="POST"
                                    action="{{ route($type === 'courses' ? 'coursier.courses.accept' : 'coursier.livraisons.accept', ['idreservation' => $task->idreservation ?? $task->idcommande]) }}">
                                    @csrf
                                    <button type="submit" class="btn-accepter mx-2">ACCEPTER</button>
                                </form>
                                <form method="POST"
                                    action="{{ route($type === 'courses' ? 'coursier.courses.cancel' : 'coursier.livraisons.cancel', ['idreservation' => $task->idreservation ?? $task->idcommande]) }}">
                                    @csrf
                                    <button type="submit" class="btn-refuser mx-2">REFUSER</button>
                                </form>
                            </div>
                        </ul>
                    </div>
                @endforeach
            </ul>
        </div>
    </section>
@endsection
