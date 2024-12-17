@extends('layouts.ubereats')

@section('title', 'Commande')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/commande.blade.css') }}">
@endsection

@section('content')
    <section>
        <div data-baseweb="block" class="main-container">
            <h3 class="css-fXLKki mt-5">Commandes à livrer :</h3>
            <ul class="liste">
                @foreach ($views as $view)
                    <div class="item-course">
                        <div class="div-course">
                            <ul>

                                <li class="courses-items">
                                    Client : <strong>{{ $view->genreuser }} {{ $view->nomuser }}
                                        {{ $view->prenomuser }}</strong>.
                                </li>
                                <li class="courses-items">
                                    Adresse de départ : <strong>{{ $view->libelle_idadresse }}, {{ $view->nomville }},
                                        ({{ $view->codepostal }})
                                    </strong>.
                                </li>
                                <li class="courses-items">
                                    Adresse de destination : <strong>{{ $view->libelle_adr_idadresse }} ,
                                        {{ $view->nomville }}, ({{ $view->codepostal }})</strong>.
                                </li>
                                <li class="courses-items">
                                    Prix : <strong>{{ $view->prixcommande }}</strong> €.
                                </li>
                                <li class="courses-items">
                                    Temps estimé : <strong>{{ $view->tempscommande }}</strong> minutes.
                                </li>
                            </ul>
                            {{--                             <div class="d-inline-flex">
                                <form method="POST" action="{{ route('commande.accept', ['idcommande' => $view->idcommande]) }}">
                                    @csrf
                                    <button target="_self" class="btn-accepter mx-2">ACCEPTER</button>
                                </form>
                                <form method="POST" action="{{ route('commande.refuse', ['idcommande' => $view->idcommande]) }}">
                                    @csrf
                                    <button target="_self" class="btn-refuser mx-2">REFUSER</button>
                                </form>
                            </div> --}}
                        </div>
                    </div>
                @endforeach
            </ul>
        </div>
    </section>
@endsection
