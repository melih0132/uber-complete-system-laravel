@extends('layouts.ubereats')

@section('title', 'Uber Eats')

@section('css')
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="{{ asset('js/script.js') }}"></script>
@endsection

@section('content')
    <main id="main-content" tabindex="-1" class="co">
        <div class="cq ak bu cr al cs ct cu cv" style="position: relative;">
            <picture>
                <source media="(max-width: 600px)" srcset="../img/ubereat.png">
                <source media="(min-width: 600px)" srcset="../img/ubereat.png">
                <img alt="" role="presentation" src="../img/ubereat.png" class="img-accueil"
                    style="width: 100%; height: auto;">
            </picture>

            <div class="main_section">
                <h1 class="bl bn bm bk d2 d3 d4 d5 d6 d7 d8 d9 da db dc dd de df">
                    Vos restos locaux livrés chez vous
                </h1>

                <div class="section_item">
                    <form action="{{ route('etablissement.index') }}" method="GET" class="filter-form">
                        <div class="main-search">
                            <!-- Recherche par ville -->
                            <div class="mx-2">
                                <input type="text" name="recherche_ville" id="recherche_ville" required
                                    class="search-bar required" placeholder="Recherchez par ville..."
                                    value="{{ request('recherche_ville') }}">
                                <ul id="suggestions-ville" class="suggestions-list"></ul>
                            </div>

                            <!-- Sélection du jour -->
                            <div class="mx-2">
                                <input type="date" id="selected_jour" name="selected_jour" class="search-bar datepicker"
                                    value="{{ request('selected_jour') ?: \Carbon\Carbon::now('Europe/Paris')->format('Y-m-d') }}"
                                    aria-label="Sélectionnez une date">
                            </div>

                            <!-- Sélection d'un créneau horaire -->
                            <div class="mx-2">
                                <select name="selected_horaires" id="selected_horaires" class="search-bar">
                                    <option value="">Sélectionnez un créneau horaire</option>
                                    @foreach ($slots as $slot)
                                        <option value="{{ $slot }}"
                                            {{ request('selected_horaires') == $slot ? 'selected' : '' }}>
                                            {{ $slot }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="e9 br bo c4 ea eb al bc ct af ec ed ee ef eg ej du ek el m-2">
                                Rechercher
                            </button>
                        </div>
                    </form>

                                        <div class="">
                        <a href="{{ route('etablissement.add') }}"
                            class="e9 br bo c4 ea eb al bc ct af ec ed ee ef eg ej du ek el m-2 text-decoration-none">
                            Ajouter votre restaurant
                        </a>
                    </div>

                    <div class="">
                        <a href="{{ url('/commande') }}"
                            class="e9 br bo c4 ea eb al bc ct af ec ed ee ef eg ei ej du ek el m-2 text-decoration-none">
                            Livreur
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <section>
        <!-- Cookie Banner -->
        <div class="cookie hidden" id="cookie-banner">
            <div class="p-3">
                <h1>Nous utilisons des cookies</h1>
                <p>
                    Cliquez sur « Accepter » pour autoriser Uber à utiliser des cookies afin de personnaliser ce
                    site, ainsi qu'à diffuser des annonces et mesurer leur efficacité sur d'autres applications et sites
                    Web, y compris les réseaux sociaux. Personnalisez vos préférences dans les paramètres des cookies ou
                    cliquez sur « Refuser » si vous ne souhaitez pas que nous utilisions des cookies à ces fins.
                    Pour en savoir plus, consultez notre
                    <a href="{{ url('/Legal') }}" class="text-decoration-underline">
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

        <!-- Cookie Settings Banner -->
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
                            nos services, telles que la connexion au compte, l'authentification et la sécurité du site.
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

        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script src="{{ asset('js/scriptCookie.js') }}"></script>
    </section>
@endsection
