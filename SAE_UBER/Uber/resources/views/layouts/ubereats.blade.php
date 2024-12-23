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
    <link rel="stylesheet" href="{{ asset('css/ubereat.blade.css') }}">
    @yield('css')
    <script src="{{ asset('js/main.js') }}"></script>
    <link rel="icon" type="image/png" href="{{ asset('img/UberEatsPetit.png') }}">
</head>

<body>
    <nav data-baseweb="header-navigation" role="navigation" class="nav-uber">
        <div class="d-flex align-items-center w-100 justify-content-between">
            <ul class="px-4">
                <li>
                    <a data-baseweb="link" href="{{ url('./UberEats') }}" target="_self" class="header-links">
                        <img src="/img/UberEats.png" alt="Uber Eats" class="logo-image">
                    </a>
                </li>
            </ul>
            <ul class="ul-links">
                <div class="d-flex justify-content-center align-items-center">
                    <li class="mx-3">
                        <a href="{{ url('/panier') }}">
                            <i class="fas fa-cart-shopping panier"></i>
                        </a>
                    </li>
                    <li class="li-links mx-2">
                        <a class="a-login" href="{{ url('/UberEats/guide') }}">Aide</a>
                    </li>
                    @php
                        $user = session('user');
                    @endphp
                    @if ($user)
                        <li class="li-links mx-2">
                            <a class="a-login" href="{{ url('/mon-compte') }}">Mon Compte</a>
                        </li>
                        <li class="css-fyrSIO mx-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="a-register" type="submit">Se déconnecter</button>
                            </form>
                        </li>
                    @else
                        <li class="li-links mx-2">
                            <a class="a-login" href="{{ url('/interface-connexion') }}">Connexion</a>
                        </li>
                        <li class="css-fyrSIO mx-2">
                            <a class="a-register" href="{{ url('/interface-inscription') }}">Inscription</a>
                        </li>
                    @endif
                </div>
            </ul>
        </div>
    </nav>

    @yield('content')

    @yield('js')
</body>

</html>
