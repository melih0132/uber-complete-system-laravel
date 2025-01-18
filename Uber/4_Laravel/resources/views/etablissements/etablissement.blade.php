@extends('layouts.ubereats')

@section('title', 'Commandez votre repas en ligne')

@section('css')
    <!-- Exemple d'intégration d'icônes FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Votre fichier CSS spécifique -->
    <link rel="stylesheet" href="{{ asset('css/etablissement.blade.css') }}">
@endsection

@section('content')

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <section>
        <div class="container">
            @if ($etablissements->count() > 0 || $produits->count() > 0)
                <form action="{{ route('etablissement.filtrage') }}" method="GET" class="filter-form">
                    <div class="filter">
                        <!-- Conserver les paramètres existants (si vous le souhaitez) -->
                        <input type="hidden" name="recherche_ville" value="{{ request('recherche_ville') }}">
                        <input type="hidden" name="selected_jour" value="{{ request('selected_jour') }}">
                        <input type="hidden" name="selected_horaires" value="{{ request('selected_horaires') }}">

                        <!-- Champ de recherche : nom de produit / établissement -->
                        <input type="text" name="recherche_produit" id="recherche_produit" class="search-input"
                            placeholder="Recherchez un produit ou un établissement..."
                            value="{{ request('recherche_produit') }}">

                        <div class="filters-grid">
                            <!-- Type de livraison -->
                            <select name="type_livraison" id="type_livraison" class="filter-select">
                                <option value="livraison" {{ $selectedTypeLivraison == 'livraison' ? 'selected' : '' }}>
                                    Livraison
                                </option>
                                <option value="retrait" {{ $selectedTypeLivraison == 'retrait' ? 'selected' : '' }}>
                                    Retrait
                                </option>
                            </select>

                            <!-- Type d'affichage -->
                            <select name="type_affichage" id="type_affichage" class="filter-select">
                                <option value="all" {{ $selectedTypeAffichage == 'all' ? 'selected' : '' }}>
                                    Établissements et Produits
                                </option>
                                <option value="etablissements"
                                    {{ $selectedTypeAffichage == 'etablissements' ? 'selected' : '' }}>
                                    Établissements
                                </option>
                                <option value="produits" {{ $selectedTypeAffichage == 'produits' ? 'selected' : '' }}>
                                    Produits
                                </option>
                            </select>

                            <!-- Filtre "Type d'établissement" (uniquement si on affiche des établissements) -->
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

                            <!-- Champs cachés pour conserver les catégories de produits filtrées -->
                            @foreach ($categoriesProduit as $categorie)
                                <input type="hidden" name="categories_produit_filtrees[]"
                                    value="{{ $categorie->idcategorie }}">
                            @endforeach

                            <!-- Filtre "Catégorie de produit" (uniquement si on affiche des produits) -->
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

                            <!-- Bouton de soumission du formulaire -->
                            <button type="submit" class="btn-panier">Rechercher</button>
                        </div>

                        <!-- Carrousel des catégories de prestation (si on affiche des établissements) -->
                        @if ($selectedTypeAffichage !== 'produits' && $categoriesPrestation->isNotEmpty())

                            <!-- Champs cachés pour conserver les prestations filtrées -->
                            @foreach ($categoriesPrestation as $categorie)
                                <input type="hidden" name="prestations_filtrees[]"
                                    value="{{ $categorie->idcategorieprestation }}">
                            @endforeach

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

                                <!-- Champ caché (pour transmettre la catégorie de prestation au submit) -->
                                <input type="hidden" name="categorie_restaurant" id="categorie_restaurant"
                                    value="{{ $selectedCategoriePrestation }}">
                            </div>
                        @endif
                    </div>
                </form>
            @endif

            <div class="main-item-grid">
                <!-- Bloc Établissements -->
                @if ($selectedTypeAffichage == 'etablissements' || $selectedTypeAffichage == 'all')
                    <div class="etablissements my-4">
                        <h1 class="div-title">Établissements</h1>
                        @if ($etablissements->isEmpty() && empty(request('recherche_produit')))
                            <p class="div-paragraph">Aucun établissement.</p>
                        @elseif ($etablissements->isEmpty())
                            <p class="div-paragraph">
                                Aucun établissement trouvé pour "{{ request('recherche_produit') }}".
                            </p>
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
                                                            {{-- Cas où l’image est stockée dans "storage/app/public" --}}
                                                            <img src="{{ asset('storage/' . $etablissement->imageetablissement) }}"
                                                                alt="{{ $etablissement->nometablissement }}">
                                                        @else
                                                            {{-- Sinon on affiche l’URL brute (externe, ou pas trouvée) --}}
                                                            <img src="{{ $etablissement->imageetablissement }}"
                                                                alt="{{ $etablissement->nometablissement }}">
                                                        @endif
                                                    </div>

                                                    <div class="etablissement-details pt-4">
                                                        <h5 class="etablissement-name">
                                                            {{ $etablissement->nometablissement }}
                                                        </h5>
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
                                {{ $etablissements->appends(request()->all())->onEachSide(1)->links('pagination::bootstrap-4') }}
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Bloc Produits -->
                @if ($selectedTypeAffichage == 'produits' || $selectedTypeAffichage == 'all')
                    <div class="produits">
                        <h1 class="div-title">Produits</h1>
                        @if ($produits->isEmpty() && empty(request('recherche_produit')))
                            <p class="div-paragraph">Aucun produit.</p>
                        @elseif ($produits->isEmpty())
                            <p class="div-paragraph">
                                Aucun produit trouvé pour "{{ request('recherche_produit') }}".
                            </p>
                        @else
                            <div class="produits-grid">
                                @foreach ($produits as $produit)
                                    <div class="produit-card">
                                        <img src="{{ Str::startsWith($produit->imageproduit, 'http') ? $produit->imageproduit : asset('storage/' . $produit->imageproduit) }}"
                                            alt="{{ $produit->nomproduit }}" class="produit-img">
                                        <h5 class="produit-name">{{ $produit->nomproduit }}</h5>
                                        <h6 class="produit-etablissement">
                                            Établi à : {{ $produit->nometablissement }}
                                        </h6>
                                        <p class="produit-price">{{ $produit->prixproduit }} €</p>

                                        {{-- Exemple de formulaire d’ajout au panier --}}
                                        <form method="POST" action="{{ route('panier.ajouter') }}">
                                            @csrf
                                            <input type="hidden" name="product" value="{{ $produit->idproduit }}">
                                            <button class="btn-panier">Ajouter au panier</button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Pagination des produits -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $produits->appends(request()->all())->links('pagination::bootstrap-4') }}
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script>
        // Carrousel minimal pour sélectionner la catégorie de prestation
        document.addEventListener('DOMContentLoaded', () => {
            const track = document.querySelector('.minimal-carousel-track');
            const leftButton = document.getElementById('btn-left');
            const rightButton = document.getElementById('btn-right');
            const cards = document.querySelectorAll('.minimal-carousel-card');
            const inputHidden = document.getElementById('categorie_restaurant');
            const searchButton = document.querySelector('.btn-panier'); // Bouton Rechercher

            // Si des éléments du carrousel n’existent pas, on n’applique pas la logique
            if (!track || !leftButton || !rightButton || cards.length === 0 || !inputHidden || !searchButton) {
                return;
            }

            let cardWidth = cards[0].offsetWidth; // Largeur d'une carte
            let totalCards = cards.length; // Nombre total de cartes
            let currentPosition = 0; // Position de défilement (en pixels)

            // Calcule le nombre de cartes visibles
            function calculateVisibleCards() {
                const trackWrapper = track.parentElement; // Conteneur parent du track
                const trackWidth = trackWrapper.offsetWidth; // Largeur "visible" du conteneur
                return Math.floor(trackWidth / cardWidth); // Nombre de cartes visibles
            }

            // Calcule la valeur max du défilement horizontal
            function calculateMaxScroll(visibleCount) {
                const trackWrapper = track.parentElement;
                const remainingWidth = cardWidth * (totalCards - visibleCount);
                const maxOffset = remainingWidth - (trackWrapper.offsetWidth % cardWidth);
                return Math.max(0, maxOffset); // Empêche un décalage négatif
            }

            let visibleCards = calculateVisibleCards();
            let maxScroll = calculateMaxScroll(visibleCards);

            // À chaque redimensionnement, recalculer les dimensions et ajuster l'affichage
            let resizeTimeout;
            window.addEventListener('resize', () => {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(handleResize, 200);
            });

            leftButton.addEventListener('click', (e) => handleNavigation(e, -1));
            rightButton.addEventListener('click', (e) => handleNavigation(e, 1));

            // Clic sur une carte => sélection de la catégorie
            track.addEventListener('click', (event) => {
                const card = event.target.closest('.minimal-carousel-card');
                if (!card) return;

                // Désélectionne toutes les cartes
                cards.forEach(c => c.classList.remove('selected'));
                // Sélectionne la carte cliquée
                card.classList.add('selected');
                // Met à jour l’input hidden
                inputHidden.value = card.dataset.id;
            });

            // Support des flèches clavier
            document.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowLeft') leftButton.click();
                if (e.key === 'ArrowRight') rightButton.click();
            });

            // Mise à jour initiale
            updateSlide();
            updateButtons();

            // ------------ FONCTIONS INTERNES ------------

            // Gérer la navigation gauche/droite
            function handleNavigation(event, direction) {
                event.preventDefault();
                const step = direction * cardWidth;
                const newPosition = currentPosition + step;

                if (newPosition >= 0 && newPosition <= maxScroll) {
                    currentPosition = newPosition;
                    updateSlide();
                }
            }

            // Met à jour le défilement et ajuste l'affichage
            function updateSlide() {
                track.style.transform = `translateX(-${currentPosition}px)`;
                updateButtons();
            }

            // Met à jour l'état des boutons de navigation
            function updateButtons() {
                leftButton.setAttribute('aria-disabled', currentPosition === 0);
                rightButton.setAttribute('aria-disabled', currentPosition >= maxScroll);
            }

            // Gérer les changements de taille de fenêtre
            function handleResize() {
                cardWidth = cards[0].offsetWidth;
                visibleCards = calculateVisibleCards();
                maxScroll = calculateMaxScroll(visibleCards);
                currentPosition = Math.min(currentPosition, maxScroll);
                updateSlide();
            }

            // Afficher un message d'information ou une alerte
            function showMessage(message) {
                alert(message); // Utilise une simple alerte
            }
        });
    </script>
    <script>
        $(document).on('click', '.ajouter-au-panier', function(e) {
            e.preventDefault();

            let productId = $(this).data('product-id');

            $.ajax({
                url: '/panier/ajouter',
                type: 'POST',
                data: {
                    product: productId,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        location.href = document.referrer; // Redirige vers la page précédente
                    } else {
                        alert('Erreur: ' + response.message);
                    }
                },
                error: function() {
                    alert('Une erreur est survenue.');
                }
            });
        });
    </script>
    <script>
        var botmanWidget = {
            frameEndpoint: '/botman/chat',
            introMessage: "Bienvenue ! Je suis votre assistant Uber. Comment puis-je vous aider ?",
            chatServer: '/botman',
            mainColor: '#06C16A',
            bubbleBackground: '#06C16A',
            bubbleAvatarUrl: '../img/UberEatsPetit.png',
            title: 'Assistant Uber',
            headerTextColor: '#000000',
            placeholderText: 'Écrivez votre message ici...',
        };
    </script>
    <script src='https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js'></script>
@endsection
