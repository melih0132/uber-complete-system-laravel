<!DOCTYPE html>
<html lang="fr">
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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
                    <a data-baseweb="link" href="{{ url('./') }}" target="_self" class="header-links">
                        <img src="/img/UberLogo.png" alt="Uber Logo" class="logo-image">
                    </a>

                </li>
            </ul>
            <ul class="css-ceakVg">
                {{-- <li class="css-hvdsGH">
                    <a data-baseweb="button" aria-label="Déplacez-vous avec Uber" href="https://m.uber.com/looking"
                        target="_self" class="header-links">Déplacez-vous avec Uber</a>
                </li> --}}
                <li class="css-hvdsGH">
                    <a data-baseweb="button" aria-label="Conduisez avec l'application Uber"
                        href="{{ url('/coursier') }}" target="_self" class="header-links">Conduire</a>
                </li>
                <li class="css-hvdsGH">
                    <a data-baseweb="button" aria-label="En savoir plus sur Uber&nbsp;Eats"
                        href="{{ url('/UberEats') }}" target="_self" class="header-links">Uber&nbsp;Eats</a>
                </li>
                <li class="css-hvdsGH">
                    <a data-baseweb="button" aria-label="En savoir plus sur Uber&nbsp;Eats"
                        href="{{ url('/UberVelo') }}" target="_self" class="header-links">Uber&nbsp;Velo</a>
                </li>
            </ul>
            <ul class="css-eOLFUs">
                <li class="css-hvdsGH">
                    <a data-baseweb="button"
                        aria-label="Connectez-vous aux sites pour les passagers, les chauffeurs et les coursiers"
                        href="{{ url('/login') }}" class="css-iqWTbl">Connexion</a>
                </li>
                <li class="css-fyrSIO">
                    <a data-baseweb="button"
                        aria-label="Inscrivez-vous pour conduire et commander des courses ou des livraisons"
                        href="{{ url('/register') }}" class="css-hrsTVr">S'inscrire</a>
                </li>
            </ul>
    </nav>

    @yield('content')

{{--     <footer>
        <div style="background-color: #000; color: #fff; padding: 20px; text-align: center;">
            <div
                style="max-width: 1200px; margin: auto; display: flex; flex-wrap: wrap; justify-content: space-between; padding: 10px 0;">
                <div style="flex: 1; min-width: 200px; margin-bottom: 15px;">
                    <h4>Uber</h4>
                    <ul style="list-style: none; padding: 0;">
                        <li><a href="/about-us" style="color: #fff; text-decoration: none;">À propos</a></li>
                        <li><a href="/careers" style="color: #fff; text-decoration: none;">Carrières</a></li>
                        <li><a href="/blog" style="color: #fff; text-decoration: none;">Blog</a></li>
                        <li><a href="/investors" style="color: #fff; text-decoration: none;">Investisseurs</a></li>
                    </ul>
                </div>
                <div style="flex: 1; min-width: 200px; margin-bottom: 15px;">
                    <h4>Services</h4>
                    <ul style="list-style: none; padding: 0;">
                        <li><a href="/ride" style="color: #fff; text-decoration: none;">Courses</a></li>
                        <li><a href="/eat" style="color: #fff; text-decoration: none;">Uber Eats</a></li>
                        <li><a href="/freight" style="color: #fff; text-decoration: none;">Uber Freight</a></li>
                        <li><a href="/business" style="color: #fff; text-decoration: none;">Uber Business</a></li>
                    </ul>
                </div>
                <div style="flex: 1; min-width: 200px; margin-bottom: 15px;">
                    <h4>Support</h4>
                    <ul style="list-style: none; padding: 0;">
                        <li><a href="/help" style="color: #fff; text-decoration: none;">Centre d'aide</a></li>
                        <li><a href="/safety" style="color: #fff; text-decoration: none;">Sécurité</a></li>
                        <li><a href="/contact" style="color: #fff; text-decoration: none;">Nous contacter</a></li>
                        <li><a href="/accessibility" style="color: #fff; text-decoration: none;">Accessibilité</a></li>
                    </ul>
                </div>
                <div style="flex: 1; min-width: 200px; margin-bottom: 15px;">
                    <h4>Suivez-nous</h4>
                    <ul style="list-style: none; padding: 0; display: flex; gap: 10px; justify-content: center;">
                        <li><a href="https://facebook.com/Uber"
                                style="color: #fff; text-decoration: none;">Facebook</a></li>
                        <li><a href="https://twitter.com/Uber" style="color: #fff; text-decoration: none;">Twitter</a>
                        </li>
                        <li><a href="https://instagram.com/Uber"
                                style="color: #fff; text-decoration: none;">Instagram</a></li>
                    </ul>
                </div>
            </div>
            <div style="border-top: 1px solid #444; margin-top: 20px; padding-top: 10px;">
                <p style="font-size: 14px; margin: 0;">© 2024 Uber Technologies Inc. Tous droits réservés.</p>
                <ul
                    style="list-style: none; padding: 0; display: flex; gap: 15px; justify-content: center; margin-top: 10px;">
                    <li><a href="/terms" style="color: #fff; text-decoration: none;">Conditions d'utilisation</a>
                    </li>
                    <li><a href="/privacy" style="color: #fff; text-decoration: none;">Politique de
                            confidentialité</a></li>
                    <li><a href="/cookies" style="color: #fff; text-decoration: none;">Préférences en matière de
                            cookies</a></li>
                </ul>
            </div>
        </div>
    </footer> --}}

    @yield('js')
</body>

</html>
