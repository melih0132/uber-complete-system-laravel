@extends('layouts.app')

@section('title', 'Guide Uber')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/aide-uber.blade.css') }}">
@endsection

@section('content')
    <section class="guide-uber my-5">
        <h1 class="text-center">Guide utilisateur</h1>
        <div class="container">
            <p class="text-center">Bienvenue sur le guide utilisateur Uber qui va vous permettre de réserver une course en toute simplicité</p>
            <h2 class="text-decoration-underline mt-5 mb-2">Barre de menu</h2>
            <p>Avec la barre menu vous aller pouvoir accéder aux différents services d'Uber.</br>
                Vous pourrez aussi vous connecter ou bien vous inscrire. </p>
            <div class="row my-4">
                <div class="col-6 d-flex justify-content-center">
                    <img class="img-fluid" src="{{ asset('img/guide-uber/navuber.png') }}">
                </div>
                <div class="col-6 d-flex justify-content-center">
                    <img class="img-fluid" src="{{ asset('img/guide-uber/loginuber.png') }}">
                </div>
            </div>
            <hr />
            <h2 class="text-decoration-underline mt-5 mb-2">Connexion / Inscription</h2>
            <p>Il vous faut vous connecter ou vous inscrire si vous n'avez pas de compte pour réserver des courses.</p>
            <div class="row my-4">
                <div class="col-6 d-flex justify-content-center">
                    <a href="{{ url('/login') }}"><img class="img-fluid" src="{{ asset('img/guide-uber/login.png') }}"></a>
                </div>
                <div class="col-6 d-flex justify-content-center">
                    <a href="{{ url('/register/passenger') }}"><img class="img-fluid" src="{{ asset('img/guide-uber/register.png') }}"></a>
                </div>
            </div>
            <hr />
            <h2 class="text-decoration-underline mt-4 mb-2">Page d'accueil</h2>
            <p>Voici la page d'accueil, point de départ de votre course.</p>
            <a href="{{ url('/') }}"><img class="img-fluid my-3" src="{{ asset('img/guide-uber/accueiluber.png') }}"></a>
            <hr />
            <h2 class="text-decoration-underline mt-4 mb-2">Prestations</h2>
            <p>En fonction de vos paramètres de course vous verrez tous les types de prestation disponibles.</p>
            <img class="img-fluid w-100 my-3" src="{{ asset('img/guide-uber/prestation.png') }}">
            <hr />
            <h2 class="text-decoration-underline mt-4 mb-2">Détails de la course</h2>
            <p>Une fois  votre type de prestation choisi vous accéderez à une page récapitulative les détails de la réservation.</br>
                Vous pourrez alors valider celle-ci ou l'annuler.</p>
            <img class="img-fluid w-100 my-3" src="{{ asset('img/guide-uber/detailcourse.png') }}">
            <hr />
            <h2 class="text-decoration-underline mt-5 mb-2">Recherche de coursier</h2>
            <p>Après avoir valider les détails de la course, votre demande de course sera transmis à tous les coursiers<br> du secteur,
                vous serez alors en attente de coursier.</p>
            <div class="row my-4">
                <div class="col-12 d-flex justify-content-center">
                    <img class="img-fluid" src="{{ asset('img/guide-uber/chauffeurwait.png') }}">
                </div>
                <p class="ml-4 mt-4">Lorsqu'un coursier aura accepté votre course, l'interface changera et vous pourrez valider le coursier.</p>
                <div class="col-12 d-flex justify-content-center mt-2">
                    <img class="img-fluid" src="{{ asset('img/guide-uber/chauffeurfind.png') }}">
                </div>
            </div>
            <hr />
            <h2 class="text-decoration-underline mt-4 mb-2">Fin de la course</h2>
            <p>Une fois votre course terminée vous accéderez à ce petit bandeau afin de valider la fin de votre course.</p>
            <img class="img-fluid w-100 my-3" src="{{ asset('img/guide-uber/fincourse.png') }}">
            <hr />
            <h2 class="text-decoration-underline mt-4 mb-2">Page facture</h2>
            <p>Enfin vous pourrez évaluer la course en la notant et en donnant un pourboire.</br>
                Vous pourrez ensuite recevoir votre facture afin de mieux comprendre la prestation.</p>
            <img class="img-fluid my-3" src="{{ asset('img/guide-uber/facture.png') }}">
        </div>
    </section>

@endsection

