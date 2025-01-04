@extends('layouts.ubereats')

@section('title', 'Commandez votre repas en ligne')

@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/etablissement.blade.css') }}">
@endsection

@section('content')
    <section>
        <div class="container">
            @if ($etablissements->count() > 0)
                <!-- Affichage si le nombre d'établissements est supérieur à 0 -->
                <form action="{{ route('etablissement.index') }}" method="GET" class="filter-form">
                    <div class="filter">
                        <!-- Champs cachés pour conserver les paramètres existants -->
                        <input type="hidden" name="recherche_ville" value="{{ request('recherche_ville') }}">
                        <input type="hidden" name="selected_jour" value="{{ request('selected_jour') }}">
                        <input type="hidden" name="selected_horaires" value="{{ request('selected_horaires') }}">

                        <!-- Recherche -->
                        <input type="text" name="recherche_produit" id="recherche_produit" class="search-input"
                            placeholder="Recherchez un produit ou un établissement..."
                            value="{{ request('recherche_produit') }}">

                        <div class="filters-grid">
                            <!-- Type d'affichage -->
                            <select name="type_affichage" id="type_affichage" class="filter-select">
                                <option value="all" {{ $selectedTypeAffichage == 'all' ? 'selected' : '' }}>
                                    Etablissements et Produits
                                </option>
                                <option value="etablissements"
                                    {{ $selectedTypeAffichage == 'etablissements' ? 'selected' : '' }}>
                                    Etablissements
                                </option>
                                <option value="produits" {{ $selectedTypeAffichage == 'produits' ? 'selected' : '' }}>
                                    Produits
                                </option>
                            </select>

                            <!-- Type de livraison -->
                            <select name="type_livraison" id="type_livraison" class="filter-select">
                                <option value="">Mode de livraison</option>
                                <option value="retrait" {{ $selectedTypeLivraison == 'retrait' ? 'selected' : '' }}>Retrait
                                </option>
                                <option value="livraison" {{ $selectedTypeLivraison == 'livraison' ? 'selected' : '' }}>
                                    Livraison
                                </option>
                            </select>

                            <!-- Filtres conditionnels -->
                            @if ($selectedTypeAffichage !== 'produits')
                                <select name="type_etablissement" id="type_etablissement" class="filter-select">
                                    <option value="">Tous les types</option>
                                    <option value="restaurant"
                                        {{ $selectedTypeEtablissement == 'restaurant' ? 'selected' : '' }}>
                                        Restaurants
                                    </option>
                                    <option value="epicerie"
                                        {{ $selectedTypeEtablissement == 'epicerie' ? 'selected' : '' }}>
                                        Épiceries
                                    </option>
                                </select>
                            @endif

                            @if ($selectedTypeAffichage !== 'etablissements')
                                <select name="categorie_produit" id="categorie_produit" class="filter-select">
                                    <option value="">Catégorie de produit</option>
                                    @foreach ($categoriesProduit as $categorieProduit)
                                        <option value="{{ $categorieProduit->idcategorie }}"
                                            {{ $selectedCategorieProduit == $categorieProduit->idcategorie ? 'selected' : '' }}>
                                            {{ $categorieProduit->nomcategorie }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                        </div>

                        @if ($selectedTypeAffichage !== 'produits' && $categoriesPrestation->isNotEmpty())
                            <div class="minimal-carousel-container">
                                <!-- Bouton gauche -->
                                <button class="minimal-carousel-btn minimal-carousel-btn-left" id="btn-left" type="button"
                                    aria-label="Défiler vers la gauche">
                                    <i class="fas fa-chevron-left"></i>
                                </button>

                                <!-- Wrapper du carrousel -->
                                <div class="minimal-carousel-wrapper">
                                    <div class="minimal-carousel-track">
                                        @foreach ($categoriesPrestation as $categoriePrestation)
                                            <div class="minimal-carousel-card"
                                                data-id="{{ $categoriePrestation->idcategorieprestation }}">
                                                <img src="{{ $categoriePrestation->imagecategorieprestation }}"
                                                    alt="{{ $categoriePrestation->libellecategorieprestation }}">
                                                <p>{{ $categoriePrestation->libellecategorieprestation }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Bouton droit -->
                                <button class="minimal-carousel-btn minimal-carousel-btn-right" id="btn-right"
                                    type="button" aria-label="Défiler vers la droite">
                                    <i class="fas fa-chevron-right"></i>
                                </button>

                                <!-- Champ caché -->
                                <input type="hidden" name="categorie_restaurant" id="categorie_restaurant"
                                    value="{{ $selectedCategoriePrestation }}">
                            </div>
                        @endif

                        <!-- Bouton de recherche -->
                        <button type="submit" class="search-button">Rechercher</button>
                    </div>
                </form>
            @endif

            <div class="main-item-grid">
                <!-- Résultats des établissements -->
                @if ($selectedTypeAffichage == 'etablissements' || $selectedTypeAffichage == 'all')
                    <div class="etablissements my-4">
                        <h1 class="div-title">Etablissements</h1>
                        @if ($etablissements->isEmpty() && empty(request('recherche_produit')))
                            <p class="div-paragraph">Aucun établissement.</p>
                        @elseif ($etablissements->isEmpty())
                            <p class="div-paragraph">Aucun établissement trouvé pour
                                "{{ request('recherche_produit') }}".</p>
                        @else
                            <div class="etablissements-grid">
                                @foreach ($etablissements as $etablissement)
                                    <div class="etablissement-container">
                                        <form method="GET"
                                            action="{{ route('etablissement.detail', ['idetablissement' => $etablissement->idetablissement]) }}">
                                            @csrf
                                            <button class="btn-etablissement">
                                                <div class="etablissement-card">
                                                    <div class="etablissement-image">
                                                        @if ($etablissement->imageetablissement && file_exists(public_path('storage/' . $etablissement->imageetablissement)))
                                                            <img src="{{ asset('storage/' . $etablissement->imageetablissement) }}"
                                                                alt="{{ $etablissement->nometablissement }}">
                                                        @else
                                                            <img src="{{ $etablissement->imageetablissement }}"
                                                                alt="{{ $etablissement->nometablissement }}">
                                                        @endif
                                                    </div>

                                                    <div class="etablissement-details pt-4">
                                                        <h5 class="etablissement-name">
                                                            {{ $etablissement->nometablissement }}</h5>
                                                        <h6 class="etablissement-type">
                                                            {{ $etablissement->typeetablissement }}
                                                        </h6>
                                                    </div>
                                                </div>
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                            <!-- Pagination des établissements -->
                            <div class="d-flex justify-content-center mt-4">
                                {{ $etablissements->appends(request()->except('page'))->onEachSide(1)->links('pagination::bootstrap-4') }}
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Résultats des produits -->
                @if ($selectedTypeAffichage == 'produits' || $selectedTypeAffichage == 'all')
                    <div class="produits">
                        <h1 class="div-title">Produits</h1>
                        @if ($produits->isEmpty() && empty(request('recherche_produit')))
                            <p class="div-paragraph">Aucun produit.</p>
                        @elseif ($produits->isEmpty())
                            <p class="div-paragraph">Aucun produit trouvé pour
                                "{{ request('recherche_produit') }}".</p>
                        @else
                            <div class="produits-grid">
                                @foreach ($produits as $produit)
                                    <div class="produit-card">
                                        <img src="{{ $produit->imageproduit }}" alt="{{ $produit->nomproduit }}"
                                            class="produit-img">
                                        <h5 class="produit-name">{{ $produit->nomproduit }}</h5>
                                        <h6 class="produit-etablissement">Établi à : {{ $produit->nometablissement }}
                                        </h6>
                                        <p class="produit-price">{{ $produit->prixproduit }} €</p>
                                        <form method="POST" action="{{ route('panier.ajouter') }}">
                                            @csrf
                                            <input name="product" value="{{ $produit->idproduit }}" type="hidden">
                                            <button type="submit" class="btn-panier">Ajouter au panier</button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Pagination des produits -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $produits->appends(request()->except('page'))->onEachSide(1)->links('pagination::bootstrap-4') }}
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Sélection des éléments du carrousel
            const track = document.querySelector('.minimal-carousel-track');
            const leftButton = document.getElementById('btn-left');
            const rightButton = document.getElementById('btn-right');
            const cards = document.querySelectorAll('.minimal-carousel-card');
            const inputHidden = document.getElementById('categorie_restaurant');

            // Si l'un des éléments nécessaires n'existe pas, on arrête le script
            if (!track || !leftButton || !rightButton || cards.length === 0 || !inputHidden) {
                // Pas de console.error nécessaire si on veut juste ignorer l'erreur silencieusement
                return;
            }

            // --- Début de la logique du carrousel ---

            // Largeur d'une carte
            let cardWidth = cards[0].offsetWidth;
            // Nombre total de cartes
            let totalCards = cards.length;
            // Position courante (en pixels) du défilement horizontal
            let currentPosition = 0;

            // Fonction : calcul du nombre de cartes visibles
            function calculateVisibleCards() {
                // On se base sur la largeur "visible" du wrapper (ou du track) plutôt que du document
                // Si votre container est .minimal-carousel-wrapper, n'hésitez pas à le cibler à la place :
                const trackWidth = track.offsetWidth;
                return Math.floor(trackWidth / cardWidth);
            }

            // Fonction : calcul du défilement maximal
            function calculateMaxScroll(visibleCount) {
                // On défile du nombre de cartes total moins celles qui sont visibles
                return cardWidth * Math.max(0, totalCards - visibleCount);
            }

            // On récupère le nombre de cartes visibles et le scroll max
            let visibleCards = calculateVisibleCards();
            let maxScroll = calculateMaxScroll(visibleCards);

            // Gestion du redimensionnement de la fenêtre
            let resizeTimeout;
            window.addEventListener('resize', () => {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(handleResize, 200);
            });

            // Boutons de navigation
            leftButton.addEventListener('click', (e) => handleNavigation(e, -1));
            rightButton.addEventListener('click', (e) => handleNavigation(e, 1));

            // Gestion du clic sur les cartes (sélection de la catégorie)
            track.addEventListener('click', (event) => {
                const card = event.target.closest('.minimal-carousel-card');
                if (!card) return;

                // Retire la classe 'selected' de toutes les cartes
                cards.forEach(c => c.classList.remove('selected'));
                // Ajoute la classe 'selected' sur la carte cliquée
                card.classList.add('selected');
                // Met à jour la valeur de l'input hidden
                inputHidden.value = card.dataset.id;
            });

            // Support clavier (flèches gauche/droite)
            document.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowLeft') leftButton.click();
                if (e.key === 'ArrowRight') rightButton.click();
            });

            // Mise à jour initiale
            updateSlide();
            updateButtons();

            // ------------------
            // FONCTIONS INTERNES
            // ------------------

            // Fonction : Navigation (défilement du carrousel)
            function handleNavigation(event, direction) {
                event.preventDefault();
                const step = direction * cardWidth;
                const newPosition = currentPosition + step;

                if (newPosition >= 0 && newPosition <= maxScroll) {
                    currentPosition = newPosition;
                    updateSlide();
                }
            }

            // Fonction : Mise à jour de la position du carrousel
            function updateSlide() {
                track.style.transform = `translateX(-${currentPosition}px)`;
                updateButtons();
            }

            // Fonction : Mise à jour de l'état des boutons (désactivation si limites atteintes)
            function updateButtons() {
                leftButton.setAttribute('aria-disabled', currentPosition === 0);
                rightButton.setAttribute('aria-disabled', currentPosition >= maxScroll);
            }

            // Fonction : Gère le redimensionnement
            function handleResize() {
                cardWidth = cards[0].offsetWidth;
                visibleCards = calculateVisibleCards();
                maxScroll = calculateMaxScroll(visibleCards);
                // On s'assure de ne pas dépasser la nouvelle limite
                currentPosition = Math.min(currentPosition, maxScroll);
                updateSlide();
            }
        });
    </script>
@endsection
