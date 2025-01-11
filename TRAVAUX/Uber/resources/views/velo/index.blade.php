@extends('layouts.app')

@section('title', 'UberVelo')

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
                    <h1 class="pb-4">Trouvez et louez un vélo avec Uber</h1>
                    <form action="{{ route('velo.index') }}" method="POST">
                        @csrf

                        <div class="address-input-container">
                            <label for="startAddress" class="form-label"></label>
                            <div class="input-with-dropdown">
                                <input type="text" id="startAddress" name="startAddress" placeholder="Adresse de départ"
                                    oninput="fetchSuggestions(this, 'startSuggestions')" required class="form-control">
                                <button type="button" class="dropdown-toggle" id="startFavoritesToggle">
                                    <i class="fas fa-star"></i>
                                </button>
                                <ul id="startFavoritesDropdown" class="favorites-dropdown hidden">

                                </ul>
                            </div>
                            <ul id="startSuggestions" class="suggestions-list"></ul>
                        </div>

                        <div class="date-container">
                            <div class="date-time-container mt-3 mr-3"
                                onclick="document.getElementById('tripDate').showPicker()">
                                <label id="tripDateLabel" data-icon="📅" class="mr-1">
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

                        <div class="duration-container mt-3">
                            <label for="duration" class="form-label">Durée de réservation</label>
                            <select name="duration" id="duration" class="form-control" value="{{$duration}}">
                                <option value="1">0 à 30 minutes</option>
                                <option value="2">1 heure</option>
                                <option value="3">1 à 3 heures</option>
                                <option value="4">3 à 8 heures</option>
                                <option value="5">1 journée</option>
                            </select>
                        </div>

                        <div id="distanceResult" class="mt-3"></div>

                        @if (session('user') && session('user.role') === 'client')
                            <button type="submit" class="mt-4" onclick="voirPrix();">Voir les prestations</button>
                        @else
                            <a href="{{ url('/login') }}" class="mt-4">Voir les prestations</a>
                        @endif
                    </form>
                </div>

                <!-- Colonne Droite : Carte Leaflet -->
                <div class="col-12 col-sm-6">
                    <div id="map">
                        <img alt="Course" class="img-fluid w-100" src="img/uber-velo.png">
                    </div>
                </div>


            </div>
        </div>
    </section>
    <div class="col-12 mt-4">
        @if (!empty($bicycles) && !$bicycles->isEmpty())
        <h2>Vélos disponibles à {{ $city }} pour le {{ request('tripDate') ?? 'Non spécifiée' }} à {{ request('tripTime') ?? 'Non spécifiée' }}H pour une durée de {{ $durationText }}</h2>
        <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID Vélo</th>
                        <th>Numéro Vélo</th>
                        <th>Adresse</th>
                        <th>Disponibilité</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bicycles as $bicycle)
                        <tr>
                            <td>{{ $bicycle->idvelo }}</td>
                            <td>{{ $bicycle->numerovelo }}</td>
                            <td>{{ $bicycle->startAddress ?? 'Adresse non disponible' }}</td>
                            <td>{{ $bicycle->estdisponible ? 'Disponible' : 'Indisponible' }}</td>
                            <td>
                                @if ($bicycle->estdisponible)
                                    <a href="{{ route('velo.reservation', [
                                        'id' => $bicycle->idvelo,
                                        'tripDate' => request('tripDate'),
                                        'tripTime' => request('tripTime')
                                    ]) }}" class="btn btn-success">Réserver</a>
                                @else
                                    <button class="btn btn-secondary" disabled>Indisponible</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @elseif (isset($startAddress))
            <p>Aucun vélo disponible à l'adresse {{ $startAddress }} pour le moment.</p>
        @endif
    </div>



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
                    <a href="{{ url('/juridique/cookie-politique') }}">
                        Déclaration relative aux cookies
                    </a>
                </p>
                <div class="d-flex justify-content-end">
                    <button id="cookie-settings" class="text-decoration-underline mx-4">Paramètres des cookies</button>
                    <button id="cookie-reject" class="mx-2">Refuser</button>
                    <button id="cookie-accept" class="">Accepter</button>
                </div>
            </div>
        </div>
        <div class="cookie-settings-banner hidden" id="cookie-settings-banner" style="display: none;">
            <div class="p-3">
                <div class="div-cookie-settings">
                    <h1 data-baseweb="heading" class="css-glaEHe">Nous utilisons des cookies</h1>
                    <div class="d-inline-flex cookie-settings">
                        <div class="d-flex flex-column cookie-settings-checkbox">
                            <label data-baseweb="checkbox" class="css-eCdekH">
                                <span class="css-gpGwpS"></span>
                                <input type="checkbox" class="css-fJmKOk" id="essential-checkbox" checked disabled>
                                <div class="text">
                                    <a data-baseweb="link" class="css-dLzUvf">Essentiel</a>
                                </div>
                            </label>
                            <label data-baseweb="checkbox" class="d-flex">
                                <span class=""></span>
                                <input type="checkbox" class="d-flex" id="advertising-checkbox">
                                <div class="text">Ciblage publicitaire</div>
                            </label>
                            <label data-baseweb="checkbox" class="d-flex">
                                <span class=""></span>
                                <input type="checkbox" class="css-fJmKOk" id="statistics-checkbox">
                                <div class="text">Statistiques</div>
                            </label>
                        </div>
                        <div id="cookie-settings-description">
                            <p data-baseweb="typo-paragraphsmall">
                                Les cookies essentiels sont nécessaires aux fonctionnalités fondamentales de notre site ou
                                de
                                nos services,
                                telles que la connexion au compte, l'authentification et la sécurité du site.
                            </p>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button data-baseweb="button" data-tracking-name="cookie-preferences-mloi-settings-close"
                            class="mx-4" id="cookie-close-settings">
                            Masquer
                        </button>
                        <button id="cookie-reject" class="mx-2">Refuser</button>
                        <button id="cookie-accept" class="">Accepter</button>
                    </div>
                </div>
            </div>
        </div>
        <script src="{{ asset('js/scriptCookie.js') }}"></script>
    </section>
    <script>
        var botmanWidget = {
            frameEndpoint: '/botman/chat',
            introMessage: "Bienvenue ! Je suis votre assistant Uber. Comment puis-je vous aider ?",
            chatServer: '/botman',
            mainColor: '#000000',
            bubbleBackground: '#FFFFFF',
            bubbleAvatarUrl: 'img/UberLogo.png',
            title: 'Assistant Uber',
            headerTextColor: '#FFFFFF',
            placeholderText: 'Écrivez votre message ici...',
        };
    </script>
    <script src='https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js'></script>
@endsection
