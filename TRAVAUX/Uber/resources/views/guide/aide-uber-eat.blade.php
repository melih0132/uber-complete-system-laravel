@extends('layouts.ubereats')

@section('title', 'Guide Uber Eats')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/aide-uber-eat.blade.css') }}">
@endsection

@section('content')
<section class="my-5">
    <h1 class="text-center">Guide utilisateur</h1>
    <div class="container">
        <p class="text-center">Bienvenue sur le guide utilisateur Uber Eats qui va vous permettre de commander tout ce dont vous avez besoin</p>
        <h2 class="text-decoration-underline mt-5 mb-2">Barre de menu</h2>
        <p>Avec la barre menu vous aller pouvoir accéder à votre panier, vous connecter ou bien vous inscrire. </p>
        <div class="d-flex justify-content-center my-4">
            <img class="img-fluid my-2 " src="{{ asset('img/guide-ubereat/menuubereat.png') }}">
        </div>
        <h2 class="text-decoration-underline mt-5 mb-4">Page d'accueil</h2>
        <p>Voici la page d'accueil, point de départ de votre commande.</p>
        <img class="img-fluid my-4" src="{{ asset('img/guide-ubereat/accueilubereat.png') }}">

        <p class="pt-5">Ici vous allez pouvoir définir tous les paramètres de livraison
        <br>C'est également ici que vous allez pouvoir ajouter votre restaurant.</p>
        <img class="img-fluid my-5" src="{{ asset('img/guide-ubereat/accueilubereat2.png') }}">
        <hr/>
        <h2 class="text-decoration-underline mt-5 mb-4">Page Etablissement</h2>
        <p>Sur cette page vous verrez tous les établissements disponibles et ouverts.</p>
        <img class="img-fluid my-5" src="{{ asset('img/guide-ubereat/etablissementfiltre.png') }}">
        <p>Choisissez l'établissement de votre choix, puis ajouter au panier les produits qui vous font envie.</p>
        <div class="row my-5">
            <div class="col-4 d-flex justify-content-center">
                <img class="img-fluid" src="{{ asset('img/guide-ubereat/etablissementclic.png') }}">
            </div>
            <div class="col-4 d-flex justify-content-center">
                <img class="img-fluid shadow-none" src="{{ asset('img/guide-ubereat/arrow.png') }}">
            </div>
            <div class="col-4 d-flex justify-content-center">
                <img class="img-fluid " src="{{ asset('img/guide-ubereat/addpanier.png') }}">
            </div>
        </div>
        <hr/>
        <h2 class="text-decoration-underline mt-5 mb-4">Panier</h2>
        <p>Sur cette page vous pouvez gérer votre panier, puis poursuivre votre commande  </p>
        <img class="img-fluid my-5" src="{{ asset('img/guide-ubereat/panier.png') }}">
    </div>
</section>
@endsection
