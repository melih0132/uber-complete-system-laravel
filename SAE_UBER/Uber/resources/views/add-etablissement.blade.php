@extends('layouts.ubereats')

@section('title', 'Ajouter un Restaurant')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/ajout-restaurant.blade.css') }}">
@endsection

@section('content')

<div class="main-container">

    <div class="add-form my-5">
        <h1>Créer votre Restaurant</h1>

        <form action="{{ route('etablissement.store') }}" method="POST">
            @csrf

            <label for="nom">Nom du Restaurant :</label>
            <input type="text" id="nometablissement" name="nometablissement" required placeholder="Entrez le nom du restaurant" value="{{ old('nom') }}">

            <label for="libelleadresse">Adresse :</label>
            <input type="text" id="libelleadresse" name="libelleadresse" required placeholder="Entrez l'adresse du restaurant" value="{{ old('adresse') }}">

            <label for="ville">Ville :</label>
            <select name="nomville" id="nomville" required>
                <option value="">Sélectionnez la ville</option>
                @foreach ($villes as $ville)
                    <option value="{{ $ville->idville }}" {{ old('ville') == $ville->idville ? 'selected' : '' }}>
                        {{ $ville->nomville }}
                    </option>
                @endforeach
            </select>

            <label for="code_postal">Code Postal :</label>
            <input type="text" id="code_postal" name="code_postal" required placeholder="Entrez le code postal" value="{{ old('code_postal') }}">
            <select name="typeetablissement" required>
                <option value="" disabled selected>Type d'etablissement</option>
                <option value="Restaurant">Restaurant</option>
                <option value="Épicerie">Epicerie</option>
            </select>
            <label for="categorie_restaurant">Catégorie(s) de Restaurant :</label>
            <select name="categorie_restaurant[]" id="categorie_restaurant" class="combobox" multiple required>
                @foreach ($categoriesPrestation as $categoriePrestation)
                    <option value="{{ $categoriePrestation->idcategorieprestation }}"
                        {{ in_array($categoriePrestation->idcategorieprestation, old('categorie_restaurant', [])) ? 'selected' : '' }}>
                        {{ $categoriePrestation->libellecategorieprestation }}
                    </option>
                @endforeach
            </select>
            @if ($errors->has('categorie_restaurant'))
                <div class="error">{{ $errors->first('categorie_restaurant') }}</div>
            @endif
            <label for="livraison">Livraison :</label>
            <input type="checkbox" id="livraison" name="livraison" value="1">

            <label for="aemporter">À emporter :</label>
            <input type="checkbox" id="aemporter" name="aemporter" value="1">

            <label for="libelleadresse">Description :</label>
            <input type="text" id="description" name="description" required placeholder="Entrez une description si souhaite">

            <label for="libelleadresse">Inserer une image :</label>
            <input type="text" id="imageetablissement" name="imageetablissement" required placeholder="Mettre une image si souhaite">

            <label for="horaire_ouverture">Horaire d'ouverture :</label>
            <input type="time" id="horaire_ouverture" name="horaire_ouverture" required value="{{ old('horaire_ouverture') }}">

            <label for="horaire_fermeture">Horaire de fermeture :</label>
            <input type="time" id="horaire_fermeture" name="horaire_fermeture" required value="{{ old('horaire_fermeture') }}">

            <div class="d-flex justify-content-center">
                <input class="btn-add" type="submit" value="Créer" action="{{ url('etablissement') }}">
            </div>
        </form>
    </div>

</div>

<div class="d-flex justify-content-end">
    <img role="presentation" src="{{ asset('img/burger.png') }}" alt="Burger">
</div>

@endsection
