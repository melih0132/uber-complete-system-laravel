@extends('layouts.ubereats')

@section('title', 'Restaurants')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/etablissement.blade.css') }}">
@endsection

@section('content')

    <section>
        <div class="main-container">
            <!-- Filter Section -->
            <form method="GET" action="{{ route('produit.index') }}" class="filter-form">
                <div class="filter">
                    <!-- Ville Filter -->
                    <select name="ville" id="ville" class="combobox">
                        <option value="">Sélectionner une ville</option>
                        @foreach ($villes as $ville)
                            <option value="{{ $ville->idville }}" {{ $selectedVille == $ville->idville ? 'selected' : '' }}>
                                {{ $ville->nomville }}
                            </option>
                        @endforeach
                    </select>

                    <!-- Delivery Type Filter -->
                    <select name="type_livraison" id="type_livraison" class="combobox">
                        <option value="">Mode de livraison</option>
                        <option value="retrait" {{ $selectedTypeLivraison == 'retrait' ? 'selected' : '' }}>Retrait</option>
                        <option value="livraison" {{ $selectedTypeLivraison == 'livraison' ? 'selected' : '' }}>Livraison
                        </option>
                    </select>

                    <select name="horaire" id="horaire" class="combobox">
                        <option value="">Période</option>
                        <option value="matin" {{ $selectedHoraire == 'matin' ? 'selected' : '' }}>Matin</option>
                        <option value="apres-midi" {{ $selectedHoraire == 'apres-midi' ? 'selected' : '' }}>Après-midi
                        </option>
                        <option value="soir" {{ $selectedHoraire == 'soir' ? 'selected' : '' }}>Soir</option>
                    </select>

                    <select name="categorie_restaurant" id="categorie_restaurant" class="combobox">
                        <option value="">Catégorie de prestation</option>
                        @foreach ($categoriesPrestation as $categoriePrestation)
                            <option value="{{ $categoriePrestation->idcategorieprestation }}"
                                {{ $selectedCategoriePrestation == $categoriePrestation->idcategorieprestation ? 'selected' : '' }}>
                                {{ $categoriePrestation->libellecategorieprestation }}
                            </option>
                        @endforeach
                    </select>

                    <select name="categorie_produit" id="categorie_produit" class="combobox">
                        <option value="">Catégorie de prestation</option>
                        @foreach ($categoriesProduit as $categorieProduit)
                            <option value="{{ $categorieProduit->idcategorieproduit }}"
                                {{ $selectedCategorieProduit == $categorieProduit->idcategorieproduit ? 'selected' : '' }}>
                                {{ $categorieProduit->libellecategorieproduit }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" class="btn btn-dark">Filtrer</button>
                </div>
            </form>

            <div class="etablissements-grid my-5">
                @foreach ($produits as $produit)
                    <div class="etablissement-card">
                        <div class="etablissement-image">
                            <img src="{{ $produit->imageproduit }}" alt="{{ $produit->nomproduit }}">
                        </div>
                        <div class="etablissement-details">
                            <h5 class="etablissement-name">{{ $produit->nomproduit }}</h5>
                        </div>
                        <div>
                            <button>Ajouter au panier</button>
                        </div>
                    </div>
                @endforeach
            </div>
                @empty
                    <p>Aucun produit trouvé. Veuillez modifier vos critères de recherche.</p>
                @endforelse
            </div>
        </div>
    </section>
@endsection
