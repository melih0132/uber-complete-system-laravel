@extends('layouts.app')

@section('title', 'Coursier')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/coursier.blade.css') }}">
@endsection

@section('content')
    <section>
        <div class="container">
            <h1 class="mt-5">Courses en attente :</h1>
            <ul class="liste my-5">
                @foreach ($views as $view)
                    <div class="item-course mt-3" data-id="{{ $view->idreservation }}">
                        <ul>
                            <li class="courses-items">
                                N° réservation : <strong>{{ $view->idreservation }}</strong>
                            </li>
                            <li class="courses-items">
                                Client : <strong>{{ $view->genreuser }} {{ $view->nomuser }}
                                    {{ $view->prenomuser }}</strong>
                            </li>
                            <li class="courses-items">
                                Date de la course :
                                @php
                                    $months = [
                                        'January' => 'Janvier',
                                        'February' => 'Février',
                                        'March' => 'Mars',
                                        'April' => 'Avril',
                                        'May' => 'Mai',
                                        'June' => 'Juin',
                                        'July' => 'Juillet',
                                        'August' => 'Août',
                                        'September' => 'Septembre',
                                        'October' => 'Octobre',
                                        'November' => 'Novembre',
                                        'December' => 'Décembre',
                                    ];
                                    $formattedDate = $view->datecourse
                                        ? \Carbon\Carbon::parse($view->datecourse)->format('d') .
                                            ' ' .
                                            $months[\Carbon\Carbon::parse($view->datecourse)->format('F')] .
                                            ' ' .
                                            \Carbon\Carbon::parse($view->datecourse)->format('Y')
                                        : 'Non spécifiée';
                                @endphp
                                <strong>{{ $formattedDate }}</strong>

                            </li>
                            <li class="courses-items">
                                Heure de départ :
                                <strong>{{ $view->heurecourse ? \Carbon\Carbon::parse($view->heurecourse)->format('H:i') : 'Non spécifiée' }}</strong>
                            </li>
                            <li class="courses-items">
                                Adresse de départ : <strong>{{ $view->libelle_idadresse }}, {{ $view->nomville }}</strong>
                            </li>
                            <li class="courses-items">
                                Adresse de destination : <strong>{{ $view->libelle_adr_idadresse }},
                                    {{ $view->nomville }}</strong>
                            </li>
                            <li class="courses-items">
                                Prix estimé : <strong>{{ $view->prixcourse }}</strong> €
                            </li>
                            <li class="courses-items">
                                Distance : <strong>{{ $view->distance }}</strong> km
                            </li>
                            <li class="courses-items">
                                Temps estimé : <strong>{{ $view->temps }}</strong> minutes
                            </li>
                            <div class="d-inline-flex pt-3">
                                <form method="POST"
                                    action="{{ route('coursier.accept', ['idreservation' => $view->idreservation]) }}">
                                    @csrf
                                    <button type="submit"  class="btn-accepter mx-2">ACCEPTER</button>
                                </form>
                                <form method="POST"
                                    action="{{ route('coursier.cancel', ['idreservation' => $view->idreservation]) }}">
                                    @csrf
                                    <button type="button" class="btn-refuser mx-2"
                                        onclick="refuserCourse({{ $view->idreservation }})">REFUSER</button>
                                </form>
                            </div>
                        </ul>
                    </div>
                @endforeach
            </ul>
        </div>
    </section>

@endsection
