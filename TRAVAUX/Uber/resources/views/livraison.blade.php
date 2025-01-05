@extends('layouts.ubereats')

@section('title', 'Panier')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/panier.blade.css') }}">
@endsection

@section('content')
    <div class="container">
        <h2 class="text-center mt-5">Choisissez votre mode de livraison</h1>
        <div class="d-flex justify-content-center">

            <form method="POST" action="{{ route('mode.livraison') }}">
                @csrf
                <div class="my-2">
                    <button type="submit" name="mode" value="retrait" class="btn-livraison">
                        Retrait
                    </button>
                    <button type="submit" name="mode" value="livraison" class="btn-livraison">
                        Livraison
                    </button>
                </div>

                <!-- Afficher le champ d'adresse uniquement si "Livraison" est sélectionné -->
                @if(session('mode') == 'livraison')
                <div class="mt-3">
                    <label for="adresse_livraison">Adresse de livraison :</label>
                    <input type="text" id="adresse_livraison" name="adresse_livraison" class="form-control" placeholder="Entrez votre adresse">
                </div>
                @endif
            </form>
        </div>
        <div class="d-flex justify-content-center">
            <h2 class="text-center mt-5">Choisissez votre carte bancaire</h1>
                @include('carte-bancaire.index', ['cartes' => $cartes])

        </div>


    </div>
@endsection
