@extends('layouts.app')

@section('title', 'Details')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/detail-course.blade.css') }}">
@endsection

@section('content')
<section>
    <div class="container">
        <h1>Détails de la réservation :</h1>
            {{-- <h3>Trajets :</h3>

            <img src="https://img.freepik.com/photos-premium/pommes-terre-cuites-au-four-turques-kumpir-fromage-saucisse-mais-mayonnaise-ketchup-olives-kumpir-est-cuisine-turque-traditionnelle_693630-867.jpg" class="img-fluid" alt="">
            <div>
                <label for="toggle-details" class="btn-toggle">Afficher plus de détails</label>
                <input type="checkbox" id="toggle-details" style="display: none;">--}}

                <div class="details">
                    <ul class="liste">
                        <li><strong>Client :</strong> {{ $reservation->genreuser }} {{ $reservation->nomuser }} {{ $reservation->prenomuser }}</li>
                        <li><strong>Adresse de départ :</strong> {{ $reservation->libelle_idadresse }}</li>
                        <li><strong>Adresse de destination :</strong> {{ $reservation->libelle_adr_idadresse }}</li>
{{--                         <li><strong>Ville :</strong> {{ $reservation->nomville }}</li> --}}
                        <li><strong>Prix estimé :</strong> {{ $reservation->prixcourse }} €</li>
                        <li><strong>Distance :</strong> {{ $reservation->distance }} km</li>
                        <li><strong>Temps estimé :</strong> {{ $reservation->temps }} minutes</li>
                        <li><strong>Statut de la course :</strong> {{ $reservation->statutcourse }}</li>
                    </ul>
                </div>
            </div>

            <div class="button-container">
                <form method="POST" action="{{ route('coursier.cancel', ['idreservation' => $idreservation])}}">
                    @csrf
                    <button type="submit" class="btn-annuler">ANNULER</button>
                </form>
                <form method="POST" {{-- action="{{ route('coursier.reserve', ['idreservation' => $idreservation])}} --}}>
                    @csrf
                    <button type="submit" onclick="window.history.back(); return false;" class="btn-reserver">FIN COURSE</button>
                </form>
            </div>
    </div>
</section>
@endsection
