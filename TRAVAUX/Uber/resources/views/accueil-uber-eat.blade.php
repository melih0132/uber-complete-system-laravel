@extends('layouts.ubereats')

@section('title', 'Uber Eats')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/accueil-ubereat.blade.css') }}">
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    <script src="{{ asset('js/scriptCookie.js') }}"></script>
@endsection

@section('content')
    <main class="main-content">
        <section class="section-container">
            <header class="section-header">
                <h1 class="header-title">Commandez vos plats favoris</h1>
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

                        <div class="form-group">
                            <label for="selected_jour" class="form-label">Date</label>
                            <input type="date" id="selected_jour" name="selected_jour" class="form-input"
                                value="{{ request('selected_jour') ?: \Carbon\Carbon::now('Europe/Paris')->format('Y-m-d') }}">
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


    {{--     <section>
        <!-- Cookie Banner -->
        <div class="cookie hidden" id="cookie-banner">
            <div class="p-3">
                <h1>Nous utilisons des cookies</h1>
                <p>
                    Cliquez sur « Accepter » pour autoriser Uber à utiliser des cookies afin de personnaliser ce site, ainsi
                    qu'à diffuser des annonces et mesurer leur efficacité sur d'autres applications et sites Web, y compris
                    les réseaux sociaux. Personnalisez vos préférences dans les paramètres des cookies ou cliquez sur «
                    Refuser » si vous ne souhaitez pas que nous utilisions des cookies à ces fins. Pour en savoir plus,
                    consultez notre
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
    </section> --}}
@endsection
