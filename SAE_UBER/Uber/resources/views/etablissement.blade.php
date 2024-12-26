    @extends('layouts.ubereats')

    @section('title', 'Commandez votre repas en ligne')

    @section('css')
        <link rel="stylesheet" href="{{ asset('css/etablissement.blade.css') }}">
    @endsection

    @section('content')

        <section>
            <div class="container">
                <form action="{{ route('etablissement.index') }}" method="GET" class="filter-form">
                    <div class="filter">

                        <!-- Champs cachés pour conserver les paramètres existants -->
                        <input type="hidden" name="recherche_ville" value="{{ request('recherche_ville') }}">
                        <input type="hidden" name="selected_jour" value="{{ request('selected_jour') }}">
                        <input type="hidden" name="selected_horaires" value="{{ request('selected_horaires') }}">

                        <!-- Barre de recherche globale (produit ou établissement) -->
                        <input type="text" name="recherche_produit" id="recherche_produit"
                            class="form-control search-bar" placeholder="Recherchez un produit ou un établissement..."
                            value="{{ request('recherche_produit') }}">

                        <!-- Sélecteur du type d'affichage -->
                        <select name="type_affichage" id="type_affichage" class="combobox">
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

                        <!-- Sélecteur du type de livraison -->
                        <select name="type_livraison" id="type_livraison" class="combobox">
                            <option value="">Mode de livraison</option>
                            <option value="retrait" {{ $selectedTypeLivraison == 'retrait' ? 'selected' : '' }}>Retrait
                            </option>
                            <option value="livraison" {{ $selectedTypeLivraison == 'livraison' ? 'selected' : '' }}>
                                Livraison</option>
                        </select>

                        <!-- Filtres conditionnels -->
                        @if ($selectedTypeAffichage !== 'produits')
                            <select name="type_etablissement" id="type_etablissement" class="combobox">
                                <option value="">Tous les types</option>
                                <option value="restaurant"
                                    {{ $selectedTypeEtablissement == 'restaurant' ? 'selected' : '' }}>
                                    Restaurants
                                </option>
                                <option value="epicerie" {{ $selectedTypeEtablissement == 'epicerie' ? 'selected' : '' }}>
                                    Épiceries
                                </option>
                            </select>
                        @endif

                        @if ($selectedTypeAffichage !== 'etablissements')
                            <select name="categorie_produit" id="categorie_produit" class="combobox">
                                <option value="">Catégorie de produit</option>
                                @foreach ($categoriesProduit as $categorieProduit)
                                    <option value="{{ $categorieProduit->idcategorie }}"
                                        {{ $selectedCategorieProduit == $categorieProduit->idcategorie ? 'selected' : '' }}>
                                        {{ $categorieProduit->nomcategorie }}
                                    </option>
                                @endforeach
                            </select>
                        @endif

                        @if ($selectedTypeAffichage !== 'produits')
                            <select name="categorie_restaurant" id="categorie_restaurant" class="combobox">
                                <option value="">Catégorie de prestation</option>
                                @foreach ($categoriesPrestation as $categoriePrestation)
                                    <option value="{{ $categoriePrestation->idcategorieprestation }}"
                                        {{ $selectedCategoriePrestation == $categoriePrestation->idcategorieprestation ? 'selected' : '' }}>
                                        {{ $categoriePrestation->libellecategorieprestation }}
                                    </option>
                                @endforeach
                            </select>
                        @endif

                        <button type="submit" class="btn btn-dark">Rechercher</button>
                    </div>
                </form>

                <div class="main-item-grid">
                    <!-- Résultats des établissements -->
                    @if ($selectedTypeAffichage == 'etablissements' || $selectedTypeAffichage == 'all')
                        <div class="etablissements my-4">
                            <h1>Etablissements</h1>
                            @if ($etablissements->isEmpty() && empty(request('recherche_produit')))
                                <p>Aucun établissement pour ce filtrage</p>
                            @elseif ($etablissements->isEmpty())
                                <p>Aucun établissement trouvé pour "{{ request('recherche_produit') }}".</p>
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
                                <div class="pagination">
                                    @if ($etablissements->onFirstPage())
                                        <span class="page-link disabled">Précédent</span>
                                    @else
                                        <a class="page-link"
                                            href="{{ $etablissements->appends(request()->except('page'))->previousPageUrl() }}">
                                            Précédent
                                        </a>
                                    @endif

                                    @if ($etablissements->hasMorePages())
                                        <a class="page-link"
                                            href="{{ $etablissements->appends(request()->except('page'))->nextPageUrl() }}">
                                            Suivant
                                        </a>
                                    @else
                                        <span class="page-link disabled">Suivant</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Résultats des produits -->
                    @if ($selectedTypeAffichage == 'produits' || $selectedTypeAffichage == 'all')
                        <div class="produits">
                            <h1>Produits</h1>
                            @if ($produits->isEmpty() && empty(request('recherche_produit')))
                                <p>Aucun produit pour ce filtrage</p>
                            @elseif ($produits->isEmpty())
                                <p>Aucun produit trouvé pour "{{ request('recherche_produit') }}".</p>
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
                        <div class="pagination my-5">
                            @if ($produits->onFirstPage())
                                <span class="page-link disabled">Précédent</span>
                            @else
                                <a class="previous_page-link"
                                    href="{{ $produits->appends(request()->except('page'))->previousPageUrl() }}">
                                    Précédent
                                </a>
                            @endif

                            @if ($produits->hasMorePages())
                                <a class="page-link"
                                    href="{{ $produits->appends(request()->except('page'))->nextPageUrl() }}">
                                    Suivant
                                </a>
                            @else
                                <span class="page-link disabled">Suivant</span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </section>

    @endsection
