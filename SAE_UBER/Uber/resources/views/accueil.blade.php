@extends('layouts.app')

{{-- @extends('layouts.connexion')
 --}}
@section('title', 'Uber')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/leaflet.css') }}">
    <link rel="stylesheet" href="{{ asset('css/accueil-uber.blade.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css">
    @yield('css2')
@endsection

@section('js')
    <script src="{{ asset('js/leaflet.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="{{ asset('js/main.js') }}" defer></script>
@endsection

@section('content')
    <section>
        <div class="main-container">
            <div class="row p-4">
                <div class="col-12 col-sm-6">
                    <h1>Allez où vous voulez avec Uber</h1>
                    <form action="{{ route('course.index') }}" method="POST">
                        @csrf
                        <!-- Adresse de départ -->
                        <input type="text" id="startAddress" name="startAddress" placeholder="Adresse de départ"
                            value="{{ old('startAddress', $startAddress ?? '') }}"
                            oninput="fetchSuggestions(this, 'startSuggestions')" required>
                        <ul id="startSuggestions" class="suggestions-list"></ul>

                        <!-- Adresse d'arrivée -->
                        <input type="text" id="endAddress" name="endAddress" class="mt-3"
                            placeholder="Adresse d'arrivée" value="{{ old('endAddress', $endAddress ?? '') }}"
                            oninput="fetchSuggestions(this, 'endSuggestions')" required>
                        <ul id="endSuggestions" class="suggestions-list"></ul>



                        <div class="date-container">
                            <div class="date-time-container mt-3 mr-3"
                                onclick="document.getElementById('tripDate').showPicker()">
                                <label id="tripDateLabel" data-icon="📅">
                                    {{ old('tripDate', isset($tripDate) ? \Carbon\Carbon::parse($tripDate)->translatedFormat('d F Y') : 'Aujourd\'hui') }}
                                </label>
                                <input type="date" id="tripDate" name="tripDate"
                                    value="{{ old('tripDate', $tripDate ?? date('Y-m-d')) }}" onchange="updateDateLabel()">
                            </div>

                            <div id="customTimePicker" class="date-time-container mt-3">
                                <label id="tripTimeLabel" data-icon="⏰">
                                    {{ old('tripTime', isset($tripTime) ? $tripTime : 'Maintenant') }}
                                </label>
                                <input type="hidden" id="tripTime" name="tripTime" value="{{ old('tripTime', $tripTime ?? '') }}">
                                <ul id="customTimeDropdown" class="dropdown-list"></ul>
                            </div>
                        </div>

                        <!-- Distance -->
                        <div id="distanceResult" class="mt-3"></div>

                        <!-- Calculer l'itinéraire -->
                        <button type="submit" class="mt-4" onclick="voirPrix();">Voir les prestations</button>
                        {{--                         ne supprimer pas c'est pour que si il veut voir les course proposer il doit se connecter mais j'active quand c'est tout fait
                         <a href="{{ url('/login') }}" class="mt-4">Voir les prestations</a> --}}
                    </form>
                </div>

                <!-- Colonne Droite : Carte Leaflet -->
                <div class="col-12 col-sm-6">
                    <div id="map"></div>
                </div>
            </div>
        </div>
    </section>

    @yield('Prestation')

    {{-- @section('Suggestion') --}}

    <section>
        <div class="main-container mt-5">
            <h3 class="css-fXLKki">Suggestions</h3>
            <ul class="gap-2 py-5 row">
                <li class="col-12 col-sm-4 my-2">
                    <a class="card-suggestion" href="{{ url('/') }}">
                        <div>
                            <div class="title-prestation">Course</div>
                            <p class="p-suggestion">
                                Allez où vous voulez avec Uber. Commandez une course en un clic et c'est parti&nbsp;!
                            </p>
                        </div>
                        <img alt="Course" class="img-suggestion" src="img/ride.png">
                    </a>
                </li>
                <li class="col-12 col-sm-4 my-2">
                    <a class="card-suggestion" href="{{ url('/UberVelo') }}">
                        <div>
                            <div class="title-prestation">Deux-roues</div>
                            <p class="p-suggestion">
                                Vous pouvez désormais trouver et louer un vélo électrique via l'application Uber.
                            </p>
                        </div>
                        <img alt="Deux-roues" class="img-suggestion" src="img/uber-velo.png">
                    </a>
                </li>
                {{--  <li class="col-12 col-sm-4 my-2">
                        <a class="card-suggestion"
                            href="https://m.uber.com/reserve/?uclick_id=374a0bd1-9406-45f7-8726-c0314f5526f7&amp;marketing_vistor_id=9e31eda8-c946-4b64-8c15-0f19645b2387"
                            data-uclick-id="374a0bd1-9406-45f7-8726-c0314f5526f7">
                            <div>
                                <div class="title-prestation">Réserver</div>
                                <p class="p-suggestion">
                                    Réservez votre course à l'avance pour pouvoir vous détendre le jour même.
                                </p>
                            </div>
                            <img alt="Réserver" class="img-suggestion" src="img/reserve_clock.png">
                        </a>
                    </li> --}}
                <li class="col-12 col-sm-4 my-2">
                    <a class="card-suggestion" href="{{ url('/UberEats') }}">
                        <div>
                            <div class="title-prestation">Courses</div>
                            <p data-baseweb="typo-paragraphxsmall" class="p-suggestion">
                                Faites livrer vos courses à votre porte avec Uber&nbsp;Eats.
                            </p>
                        </div>
                        <img alt="Courses" class="img-suggestion" src="img/course.png">
                    </a>
                </li>
            </ul>
        </div>
    </section>

    {{-- @endsection --}}

    <!-- Section Cookies -->
    <section>
        <div class="cookie hidden" id="cookie-banner">
            <div class="p-3">
                <h1>Nous utilisons des cookies</h1>
                <p>
                    Cliquez sur « Accepter » pour autoriser Uber à utiliser des cookies afin de personnaliser ce
                    site, ainsi qu'à diffuser des annonces et mesurer leur efficacité sur d'autres applications et sites
                    Web, y compris les réseaux sociaux. Personnalisez vos préférences dans les paramètres des cookies ou
                    cliquez sur « Refuser » si vous ne souhaitez pas que nous utilisions des cookies à ces fins.
                    Pour en savoir plus, consultez notre
                    <a href="{{ url('/Legal') }}">
                        Déclaration relative aux cookies
                    </a>
                </p>
                <div class="d-flex justify-content-end">
                    <button id="cookie-settings" class="text-decoration-underline mx-4">Paramètres des cookies</button>
                    <button id="cookie-reject" class="mr-4">Refuser</button>
                    <button id="cookie-accept" class="ml-4">Accepter</button>
                </div>
            </div>
        </div>
        <div class="cookie-settings-banner hidden m-5" id="cookie-settings-banner" style="display: none;">
            <div class="css-etreRh">
                <div class="css-bcvoMj">
                    <h1 data-baseweb="heading" class="css-glaEHe">Nous utilisons des cookies</h1>
                    <div class="css-bsBbCu row">
                        <div class="css-eFLfxY col">
                            <label data-baseweb="checkbox" class="css-eCdekH">
                                <span class="css-gpGwpS"></span>
                                <input type="checkbox" class="css-fJmKOk" id="essential-checkbox" checked disabled>
                                <div class="css-dvKwsj">
                                    <a data-baseweb="link" class="css-dLzUvf">Essentiel</a>
                                </div>
                            </label>
                            <label data-baseweb="checkbox" class="css-eCdekH">
                                <span class="css-lhNqTx"></span>
                                <input type="checkbox" class="css-fJmKOk" id="advertising-checkbox">
                                <div class="css-dvKwsj">Ciblage publicitaire</div>
                            </label>
                            <label data-baseweb="checkbox" class="css-eCdekH">
                                <span class="css-lhNqTx"></span>
                                <input type="checkbox" class="css-fJmKOk" id="statistics-checkbox">
                                <div class="css-dvKwsj">Statistiques</div>
                            </label>
                        </div>
                        <p data-baseweb="typo-paragraphsmall" class="css-igflQV css-lnLvkz col" id="cookie-description">
                            Les cookies essentiels sont nécessaires aux fonctionnalités fondamentales de notre site ou de
                            nos services,
                            telles que la connexion au compte, l'authentification et la sécurité du site.
                        </p>
                    </div>
                    <div class="css-gTwEmV">
                        <button data-baseweb="button" data-tracking-name="cookie-preferences-mloi-settings-close"
                            class="css-heRtxy" id="cookie-close-settings">
                            Masquer
                        </button>
                        <button id="cookie-reject" class="mr-4">Refuser</button>
                        <button id="cookie-accept" class="ml-4">Accepter</button>
                    </div>
                </div>
            </div>
        </div>
        <script src="{{ asset('js/scriptCookie.js') }}"></script>
    </section>
@endsection
