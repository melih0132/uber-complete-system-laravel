<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Métadonnées de base -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Titre de la page -->
    <title>@yield('title')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('img/UberEatsPetit.png') }}">

    <!-- Feuilles de styles externes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@400;700&family=Segoe+UI&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <!-- Feuilles de styles personnalisées -->
    <link rel="stylesheet" href="{{ asset('css/ubereat.blade.css') }}">
    @yield('css')

    <!-- Scripts -->
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>


<body>
    <nav data-baseweb="header-navigation" role="navigation" class="nav-uber">
        <div class="d-flex align-items-center w-100 justify-content-between">
            @php
                $user = session('user');
            @endphp
            <div class="d-flex align-items-center w-100">
                <!-- Logo Section -->
                <ul class="px-4">
                    <li>
                        <a data-baseweb="link" href="{{ url('/UberEats') }}" target="_self" class="header-links">
                            <img src="/img/UberEats.png" alt="Uber Eats Logo" class="logo-image">
                        </a>
                    </li>
                </ul>
                <ul class="ul-links">

                    @if ($user && $user['role'] === 'livreur')
                        <li class="pr-1">
                            <a data-baseweb="button" aria-label="Accéder aux courses"
                                href="{{ route('coursier.livraisons.index') }}" target="_self"
                                class="header-link">Livraisons
                                en
                                attente</a>
                        </li>
                        <li class="pr-1">
                            <a data-baseweb="button" aria-label="Accéder aux courses"
                                href="{{ route('livreur.livraisons.encours') }}" target="_self"
                                class="header-link">Livraisons
                                en
                                cours</a>
                        </li>
                    @endif

                    @if ($user && $user['role'] === 'restaurateur')
                        <li class="pr-1">
                            <a data-baseweb="button" aria-label="Ajouter un établissement"
                                href="{{ route('etablissement.create') }}" target="_self" class="header-link">Ajouter
                                Établissement</a>
                        </li>
                        <li class="pr-1">
                            <a data-baseweb="button" aria-label="Ajouter un produit"
                                href="{{ route('manager.produits.create') }}" target="_self" class="header-link">Ajouter
                                Produit</a>
                        </li>
                        <li class="pr-1">
                            <a data-baseweb="button" aria-label="Voir tous les produits"
                                href="{{ route('manager.produits.index') }}" target="_self" class="header-link">Liste
                                des
                                Produits</a>
                        </li>
                    @endif

                    @if ($user && $user['role'] === 'responsable')
                        <li class="pr-1">
                            <a data-baseweb="button" aria-label="Commandes à livrer dans la prochaine heure"
                                href="{{ route('responsable.commandes') }}" target="_self"
                                class="header-link">Commandes
                                Urgentes</a>
                        </li>
                    @endif

                    @if ($user && $user['role'] === 'commande')
                        <li class="pr-1">
                            <a data-baseweb="button" aria-label="Voir les courses demandées"
                                href="{{ route('commandes.index') }}" target="_self" class="header-link">Commandes
                                Refusées</a>
                        </li>
                    @endif

                    @if ($user && $user['role'] === 'client')
                        <li class="pr-1">
                            <a href="{{ route('commande.mesCommandes') }}" class="header-link">Mes Commandes</a>
                        </li>
                    @endif
                </ul>
            </div>
            <!-- Navigation Links -->
            <ul class="ul-links">
                <div class="d-flex justify-content-start align-items-center">
                    <!-- Panier -->
                    @if ($user && $user['role'] === 'client')
                        <li class="mx-3">
                            <a href="{{ url('/panier') }}" aria-label="Panier">
                                <i class="fas fa-cart-shopping panier"></i>
                            </a>
                        </li>
                        <!-- Help -->
                        <li class="li-links header-links mx-2">
                            <a class="a-login" href="{{ url('/UberEats/guide') }}">Aide</a>
                        </li>
                    @endif

                    <!-- Authentication Links -->
                    @if ($user)
                        <!-- Mon Compte -->
                        <li class="li-links header-links mx-2">
                            <a class="a-login" href="{{ url('/myaccount') }}">Mon Compte</a>
                        </li>
                        <!-- Logout -->
                        <li class="css-fyrSIO header-links mx-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="a-register" type="submit">Se déconnecter</button>
                            </form>
                        </li>
                    @else
                        <!-- Connexion -->
                        <li class="li-links header-links mx-2">
                            <a class="a-login" href="{{ url('/interface-connexion') }}">Connexion</a>
                        </li>
                        <!-- Inscription -->
                        <li class="css-fyrSIO header-links mx-2">
                            <a class="a-register" href="{{ url('/interface-inscription') }}">Inscription</a>
                        </li>
                    @endif
                </div>
            </ul>
        </div>
    </nav>

    <div class="container" style="min-height: 100vh; padding: 2rem;">
        @yield('content')
    </div>

    <footer class="footer-container mt-5">
        <div class="footer-links">
            <a href="{{ route('privacy') }}">Politique de Confidentialité</a>
        </div>
        <div class="footer-info">
            <p>&copy; {{ date('Y') }} Uber Eats. Tous droits réservés.</p>
        </div>
    </footer>

    @yield('js')
</body>

</html>
