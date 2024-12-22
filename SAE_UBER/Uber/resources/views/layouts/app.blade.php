<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@400;700&family=Segoe+UI&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/app.blade.css') }}">
    @yield('css')
    <script src="{{ asset('js/main.js') }}"></script>
    <link rel="icon" type="image/png" href="{{ asset('img/ride.png') }}">
</head>

<body>
    <nav data-baseweb="header-navigation" role="navigation" class="css-esMPmm">
        <div class="d-flex align-items-center w-100 justify-content-between" data-testid="nav-grid" tabindex="-1">
            <ul class="css-kpeoJm">
                <li class="css-dkqmeR">
                    <a data-baseweb="link" href="{{ url('./') }}" target="_self">
                        <img src="/img/UberLogo.png" alt="Uber Logo" class="logo-image">
                    </a>
                </li>
            </ul>
            <ul class="css-ceakVg">
                @php
                    $user = session('user');
                @endphp

                @if ($user && $user['role'] === 'coursier')
                    <li class="css-hvdsGH">
                        <a data-baseweb="button" aria-label="Conduisez avec l'application Uber"
                            href="{{ url('/coursier') }}" target="_self" class="header-links">Conduire</a>
                    </li>
                @endif

                @if ($user && $user['role'] === 'client')
                    <li class="css-hvdsGH">
                        <a data-baseweb="button" aria-label="Commander un Uber" href="{{ url('./') }}"
                            target="_self" class="header-links">Réserver un Uber</a>
                    </li>
                @endif

                <li class="css-hvdsGH">
                    <a data-baseweb="button" aria-label="En savoir plus sur Uber&nbsp;Eats"
                        href="{{ url('/UberEats') }}" target="_self" class="header-links">Uber&nbsp;Eats</a>
                </li>
                <li class="css-hvdsGH">
                    <a data-baseweb="button" aria-label="En savoir plus sur Uber&nbsp;Velo"
                        href="{{ url('/UberVelo') }}" target="_self" class="header-links">Uber&nbsp;Velo</a>
                </li>
                <li class="css-hvdsGH">
                    <a data-baseweb="button" aria-label="Besoin d'aide" href="{{ url('/Uber/guide') }}" target="_self"
                        class="header-links">Besoin&nbsp;d'aide&nbsp;?</a>
                </li>
            </ul>
            <ul class="css-eOLFUs">
                @if ($user)
                    <li class="css-hvdsGH">
                        <a data-baseweb="button" aria-label="Mon compte" href="{{ url('/mon-compte') }}"
                            class="css-iqWTbl">Mon Compte</a>
                    </li>
                    <li class="css-fyrSIO">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="css-hrsTVr" type="submit">Se déconnecter</button>
                        </form>
                    </li>
                @else
                    <li class="css-hvdsGH">
                        <a data-baseweb="button" aria-label="Se connecter" href="{{ url('/interface-connexion') }}"
                            class="css-iqWTbl">Se connecter</a>
                    </li>
                    <li class="css-fyrSIO">
                        <a data-baseweb="button" aria-label="S'inscrire" href="{{ url('/register') }}"
                            class="css-hrsTVr">S'inscrire</a>
                    </li>
                @endif
            </ul>
        </div>
    </nav>

    <div class="container">
        {{-- <section>
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
            </div>
        </section> --}}

        @yield('content')
    </div>

    @yield('js')
</body>

</html>
