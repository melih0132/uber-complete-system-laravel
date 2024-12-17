<!DOCTYPE html>
<html lang="fr">
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/app.blade.css') }}">
    @yield('css')
    <script src="{{ asset('js/main.js') }}"></script>
    <link rel="icon" type="image/png" href="{{ asset('img/ride.png') }}">
</head>

<body>
    <nav data-baseweb="header-navigation" role="navigation" class="css-esMPmm">
        <div class="css-ivGTTH" data-testid="nav-grid" tabindex="-1">
            <ul class="css-kpeoJm">
                <li class="css-dkqmeR">
                    <a data-baseweb="link" href="{{ url('./connexion') }}" target="_self" class="header-links">
                        <img src="/img/UberLogo.png" alt="Uber Logo" class="logo-image">
                    </a>

                </li>
            </ul>
            <ul class="css-ceakVg">
                <li class="css-hvdsGH">
                    <a data-baseweb="button" aria-label="Déplacez-vous avec Uber"
                     href="https://m.uber.com/looking" target="_self" class="header-links">Déplacez-vous avec Uber</a>
                </li>
                <li class="css-hvdsGH">
                    <a data-baseweb="button" aria-label="En savoir plus sur Uber&nbsp;Eats"
                        href="{{ url('/UberEats') }}" target="_self"
                        class="header-links">Uber&nbsp;Eats</a>
                </li>
                <li class="css-hvdsGH">
                    <a data-baseweb="button" aria-label="En savoir plus sur Uber&nbsp;Eats"
                        href="{{ url('/UberVelo') }}" target="_self"
                        class="header-links">Uber&nbsp;Velo</a>
                </li>
            </ul>
            <ul class="css-eOLFUs">
                <li class="css-hvdsGH">
                    <a data-baseweb="button"
                        aria-label="Mon compte"
                        href="{{ url('/showAccount') }}" class="css-iqWTbl">Mon Compte</a>
                </li>
                <li class="css-fyrSIO">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="css-hrsTVr" type="submit">Se déconnecter</button>
                    </form>
                </li>
            </ul>
    </nav>
    <div class="container">
        <section>
            <div class="main-container">
                <div data-baseweb="block" class="css-dGDWFE">
                    <div data-testid="content-group-dynamic" class="css-cYTjpz">
                        <div data-baseweb="block" class="css-PKJb">
                            <div class="css-etjCRc">
                                <div class="css-hPnljU">
                                    <h1 class="css-bIdYaZ">@yield('name_client')</h1>
                                </div>
                            </div>
                        </div>
                        <div data-baseweb="block" class="css-PKJb">

                        </div>
                    </div>
                </div>
        </section>
        @yield('content')

    </div>
    {{-- <footer>
        <div class="css-ivGTTH" data-testid="nav-grid" tabindex="-1">
            <ul class="css-kpeoJm">
                <li class="css-dkqmeR">
                    <a data-baseweb="link" href="{{ url('./') }}" target="_self" class="header-links">Uber</a>
                </li>
            </ul>
            <ul class="css-ceakVg">
                <li class="css-hvdsGH">
                    <a data-baseweb="button" aria-label="Conduisez avec l'application Uber"
                        href="{{ url('/coursier') }}" target="_self" class="header-links">Conduire</a>
                </li>
                </li>
                <li class="css-hvdsGH">
                    <a data-baseweb="button" aria-label="En savoir plus sur Uber&nbsp;Eats"
                        href="{{ url('/commande') }}" target="_self"
                        data-tracking-name="_79e70995-edbc-4fed-b2ca-3904aba7ac2d_links[3].link_cta9"
                        class="header-links">Uber&nbsp;Eats</a>
                </li>
            </ul>
            <ul class="css-eOLFUs">
                <li class="css-hvdsGH">
                    <button data-baseweb="button"
                        aria-label="Connectez-vous aux sites pour les passagers, les chauffeurs et les coursiers"
                        class="css-iqWTbl">Connexion</button>
                </li>
                <li class="css-fyrSIO">
                    <button data-baseweb="button"
                        aria-label="Inscrivez-vous pour conduire et commander des courses ou des livraisons"
                        class="css-hrsTVr">S'inscrire</button>
                </li>
            </ul>
        </div>

    </footer> --}}
    @yield('js')
</body>

</html>
