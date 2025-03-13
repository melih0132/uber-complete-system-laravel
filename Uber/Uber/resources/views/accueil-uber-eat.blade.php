@extends('layouts.ubereats')

@section('title', 'Uber Eats')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/accueil-ubereat.blade.css') }}">
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    <script src="{{ asset('js/scriptCookie.js') }}"></script>

    {{-- DATE PIECKER --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
@endsection

@section('content')
    <main class="main-content">
        <section class="section-container">
            <header class="section-header">
                <h1 class="header-title">Vos restos locaux livrés chez vous</h1>
                <p class="header-description">Trouvez et faites-vous livrer les meilleurs plats des restaurants proches de
                    chez vous.</p>
            </header>

            <div class="form-section">
                <form action="{{ route('etablissement.index') }}" method="GET" class="form-container">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="recherche_ville" class="form-label">Ville</label>
                            <input type="text" name="recherche_ville" id="recherche_ville" class="form-input" required
                                placeholder="Recherchez une ville" value="{{ request('recherche_ville') }}">
                        </div>

                        <div class="form-group date-picker-container">
                            <label for="selected_jour" class="form-label">Date</label>
                            <div class="input-group">
                                <input type="text" id="selected_jour" name="selected_jour"
                                    class="form-input flatpickr-date"
                                    value="{{ request('selected_jour') ?: \Carbon\Carbon::now('Europe/Paris')->format('d/m/Y') }}"
                                    placeholder="jj/mm/aaaa">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="selected_horaires" class="form-label">Créneau horaire</label>
                            <select name="selected_horaires" id="selected_horaires" class="form-select">
                                <option value="" {{ empty($selectedHoraire) ? 'selected' : '' }}>Sélectionnez un
                                    créneau</option>
                                @foreach ($slots as $slot)
                                    <option value="{{ $slot }}" {{ $selectedHoraire === $slot ? 'selected' : '' }}>
                                        {{ $slot }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-row">
                            <button type="submit" class="form-button">Rechercher</button>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </main>


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
                    <a href="{{ url('/juridique/privacy') }}">
                        Déclaration relative aux cookies
                    </a>
                </p>
                <div class="d-flex justify-content-start">
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
    </section>
    <script>
        var botmanWidget = {
            frameEndpoint: '/botman/chat',
            introMessage: "Bienvenue ! Je suis votre assistant Uber. Comment puis-je vous aider ?",
            chatServer: '/botman',
            mainColor: '#06C16A',
            bubbleBackground: '#06C16A',
            bubbleAvatarUrl: 'img/UberEatsPetit.png',
            title: 'Assistant Uber',
            headerTextColor: '#000000',
            placeholderText: 'Écrivez votre message ici...',
        };
    </script>
    <script src='https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js'></script>
@endsection
