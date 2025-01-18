<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Métadonnées de base -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('img/UberLogo.png') }}">

    <!-- Feuilles de styles externes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@400;700&family=Segoe+UI&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <!-- Feuilles de styles personnalisées -->
    <link rel="stylesheet" href="{{ asset('css/app.blade.css') }}">
    <link rel="preload" href="/fonts/UberMoveBold.otf" as="font" type="font/otf" crossorigin="anonymous">
    <link rel="preload" href="/fonts/UberMoveMedium.otf" as="font" type="font/otf" crossorigin="anonymous">
    @yield('css')

    <!-- Scripts -->
    <script src="{{ asset('js/main.js') }}"></script>
</head>

<body>
    <nav data-baseweb="header-navigation" role="navigation" class="navbar-uber">
        <div class="d-flex align-items-center w-100 justify-content-between">
            @php
                $user = session('user');
            @endphp
            <ul>
                @if (!$user || ($user['role'] === 'client' && $user['typeclient'] === 'Uber'))
                    <li>
                        <a data-baseweb="link" href="{{ url('./') }}" target="_self">
                            <img src="/img/Uber.png" alt="Uber Logo" class="logo-image"
                                style="width: 50px; height: auto;">
                        </a>
                    </li>
                @else
                    <li>
                        <img src="/img/Uber.png" alt="Uber Logo" class="logo-image" style="width: 50px; height: auto;">
                    </li>
                @endif
            </ul>
            <ul class="ul-links">
                @if ($user && $user['role'] === 'coursier')
                    <li class="pr-1">
                        <a data-baseweb="button" aria-label="Accéder aux courses"
                            href="{{ route('coursier.courses.index') }}" target="_self" class="header-links">Courses en
                            attente</a>
                    </li>
                    <li class="pr-1">
                        <a data-baseweb="button" aria-label="Voir les détails d'entretien"
                            href="{{ route('coursier.entretien') }}" target="_self" class="header-links">Entretiens
                            Uber</a>
                    </li>
                    <li>
                        <a href="{{ route('conducteurs.demandes', $user['id']) }}" class="header-links">Demandes
                            d'aménagement(s)</a>
                    </li>
                @endif

                @if ($user && $user['role'] === 'restaurateur')
                    <li class="pr-1">
                        <a data-baseweb="button" aria-label="Ajouter un établissement"
                            href="{{ route('etablissement.create') }}" target="_self" class="header-links">Ajouter
                            Établissement</a>
                    </li>
                    <li class="pr-1">
                        <a data-baseweb="button" aria-label="Ajouter un produit"
                            href="{{ route('manager.produits.create') }}" target="_self" class="header-links">Ajouter
                            Produit</a>
                    </li>
                    <li class="pr-1">
                        <a data-baseweb="button" aria-label="Voir tous les produits"
                            href="{{ route('manager.produits.index') }}" target="_self" class="header-links">Liste des
                            Produits</a>
                    </li>
                @endif

                @if ($user && $user['role'] === 'responsable')
                    <li class="pr-1">
                        <a data-baseweb="button" aria-label="Commandes à livrer dans la prochaine heure"
                            href="{{ route('responsable.commandes') }}" target="_self" class="header-links">Commandes
                            Urgentes</a>
                    </li>
                @endif

                @if ($user && $user['role'] === 'rh')
                    <li class="pr-1">
                        <a data-baseweb="button" aria-label="Voir les entretiens en attente"
                            href="{{ route('entretiens.index') }}" target="_self" class="header-links">Entretiens en
                            Attente</a>
                    </li>
                    <li class="pr-1">
                        <a data-baseweb="button" aria-label="Voir les entretiens planifiés"
                            href="{{ route('entretiens.plannifies') }}" target="_self"
                            class="header-links">Planifiés</a>
                    </li>
                    <li class="pr-1">
                        <a data-baseweb="button" aria-label="Voir les entretiens terminés"
                            href="{{ route('entretiens.termines') }}" target="_self" class="header-links">Terminés</a>
                    </li>
                @endif

                @if ($user && $user['role'] === 'logistique')
                    <li class="pr-1">
                        <a data-baseweb="button" aria-label="Ajouter un véhicule"
                            href="{{ route('logistique.coursiers.select') }}" target="_self"
                            class="header-links">Ajouter Véhicule</a>
                    </li>
                    <li class="pr-1">
                        <a data-baseweb="button" aria-label="Voir les véhicules disponibles"
                            href="{{ route('logistique.vehicules') }}" target="_self"
                            class="header-links">Véhicules</a>
                    </li>
                    <li class="pr-1">
                        <a data-baseweb="button" aria-label="Gérer les modifications"
                            href="{{ route('logistique.modifications') }}" target="_self"
                            class="header-links">Aménagements</a>
                    </li>
                @endif

                @if ($user && $user['role'] === 'administratif')
                    <li class="pr-1">
                        <a data-baseweb="button" aria-label="Valider la finalisation"
                            href="{{ route('admin.index') }}" target="_self" class="header-links">Finaliser création
                            coursier</a>
                    </li>
                @endif

                @if ($user && $user['role'] === 'facturation')
                    <li class="pr-1">
                        <a data-baseweb="button" aria-label="Voir les courses d'un coursier"
                            href="{{ url('/facturation') }}" target="_self" class="header-links">Facturation</a>
                    </li>
                @endif

                @if ($user && $user['role'] === 'course')
                    <li class="pr-1">
                        <a data-baseweb="button" aria-label="Voir les courses demandées"
                            href="{{ route('serviceCourse.index') }}" target="_self" class="header-links">Courses
                            Demandées</a>
                    </li>
                    <li class="pr-1">
                        <a data-baseweb="button" aria-label="Analyses et Performances"
                            href="{{ route('serviceCourse.analyse') }}" target="_self"
                            class="header-links">Analayses et Performances</a>
                    </li>
                @endif

                @if ($user && $user['role'] === 'commande')
                    <li class="pr-1">
                        <a data-baseweb="button" aria-label="Voir les courses demandées"
                            href="{{ route('commandes.index') }}" target="_self" class="header-links">Commandes</a>
                    </li>
                @endif

                @if ($user && $user['role'] === 'juridique')
                    <li class="pr-1">
                        <a data-baseweb="button" aria-label="Voir les courses demandées"
                            href="{{ route('privacy') }}" target="_self" class="header-links">Politique de
                            confidentialité</a>
                    </li>
                    <li class="pr-1">
                        <a data-baseweb="button" aria-label="Analyses et Performances"
                            href="{{ route('juridique.anonymisation') }}" target="_self"
                            class="header-links">Anonymisation</a>
                    </li>
                @endif

                @if (!$user || ($user['role'] === 'client' && $user['typeclient'] === 'Uber'))
                    <li class="pr-1">
                        <a href="{{ url('./') }}" class="header-links">Déplacez-vous avec Uber</a>
                    </li>
                    <li class="pr-1">
                        <a data-baseweb="button" aria-label="En savoir plus sur Uber&nbsp;Velo"
                            href="{{ route('velo.show') }}" target="_self" class="header-links">Uber&nbsp;Velo
                        </a>
                    </li>
                    <li class="pr-1">
                        <a data-baseweb="button" aria-label="En savoir plus sur Uber&nbsp;Eats"
                            href="{{ url('/UberEats') }}" target="_self" class="header-links">Uber&nbsp;Eats
                        </a>
                    </li>
                    <li class="pr-1">
                        <a data-baseweb="button" aria-label="Besoin d'aide" href="{{ url('/Uber/guide') }}"
                            target="_self"class="header-links">Besoin&nbsp;d'aide&nbsp;?
                        </a>
                    </li>
                @endif
            </ul>
            <ul class="ul-link-login d-flex align-items-center">
                @if ($user)
                    <li class="pr-1">
                        <a data-baseweb="button" aria-label="Accéder à votre compte" href="{{ url('/myaccount') }}"
                            class="a-login">Mon Compte</a>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="a-register" type="submit">Se déconnecter</button>
                        </form>
                    </li>
                @else
                    <li class="pr-1">
                        <a data-baseweb="button" aria-label="Se connecter à votre compte"
                            href="{{ url('/interface-connexion') }}" class="a-login">Connexion</a>
                    </li>
                    <li>
                        <a data-baseweb="button" aria-label="Créer un compte"
                            href="{{ url('/interface-inscription') }}" class="a-register">Inscription</a>
                    </li>
                @endif
            </ul>
        </div>
    </nav>

    <div class="container" style="min-height: 100vh; padding: 2rem;">
        @yield('content')
    </div>

    <footer>
        <div class="footer-container mt-5">
            <div class="footer-links">
                <a href="{{ route('privacy') }}">Politique de Cookies</a>
            </div>
            <div class="footer-info">
                <p>&copy; {{ date('Y') }} Uber. Tous droits réservés.</p>
                <p>
                    Ce site utilise des cookies pour améliorer l'expérience utilisateur.
                    <a href="{{ route('privacy') }}">En savoir plus</a>.
                </p>
            </div>
        </div>
    </footer>

    @yield('js')
</body>

</html>
