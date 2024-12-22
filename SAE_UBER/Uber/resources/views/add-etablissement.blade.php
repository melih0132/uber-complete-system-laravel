@extends('layouts.ubereats')

@section('title', 'Ajouter votre établissement')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/ajout-restaurant.blade.css') }}">
@endsection

@section('content')

    <div class="container">

        <div class="add-form my-5">
            <h1>Créer votre établissement</h1>

            <form action="{{ route('etablissement.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Nom de l'établissement -->
                <label for="nometablissement">Nom de l'établissement :</label>
                <input type="text" id="nometablissement" name="nometablissement" required
                    placeholder="Entrez le nom de l'établissement" value="{{ old('nometablissement') }}">
                @error('nometablissement')
                    <div class="error">{{ $message }}</div>
                @enderror

                <!-- Adresse de l'établissement -->
                <label for="libelleadresse">Adresse de l'établissement :</label>
                <input type="text" id="libelleadresse" name="libelleadresse" required
                    placeholder="Entrez l'adresse de l'établissement" value="{{ old('libelleadresse') }}">
                @error('libelleadresse')
                    <div class="error">{{ $message }}</div>
                @enderror

                <!-- Ville -->
                <label for="nomville">Ville :</label>
                <input type="text" id="nomville" name="nomville" required placeholder="Entrez le nom de la ville"
                    value="{{ old('nomville') }}">
                @error('nomville')
                    <div class="error">{{ $message }}</div>
                @enderror

                <!-- Code Postal -->
                <label for="codepostal">Code Postal :</label>
                <input type="text" id="codepostal" name="codepostal" required placeholder="Entrez le code postal"
                    value="{{ old('codepostal') }}">
                @error('codepostal')
                    <div class="error">{{ $message }}</div>
                @enderror

                <!-- Type d'établissement -->
                <label for="typeetablissement">Type d'établissement :</label>
                <select name="typeetablissement" id="typeetablissement" required>
                    <option value="" disabled selected>Sélectionnez le type d'établissement</option>
                    <option value="Restaurant" {{ old('typeetablissement') == 'Restaurant' ? 'selected' : '' }}>Restaurant
                    </option>
                    <option value="Épicerie" {{ old('typeetablissement') == 'Épicerie' ? 'selected' : '' }}>Épicerie
                    </option>
                </select>
                @error('typeetablissement')
                    <div class="error">{{ $message }}</div>
                @enderror

                <!-- Catégories -->
                <label for="categorie_restaurant">Catégorie(s) de Restaurant :</label>
                <input type="text" id="categorie_restaurant" name="categorie_restaurant"
                    placeholder="Saisissez des catégories existantes...">

                <!-- Livraison -->
                <label for="livraison">Livraison :</label>
                <div class="d-inline-flex pl-3">
                    <input type="radio" class="mx-2" id="livraison_oui" name="livraison" value="1"
                        {{ old('livraison') == '1' ? 'checked' : '' }}>
                    <label for="livraison_oui">Oui</label>

                    <input type="radio" class="mx-2" id="livraison_non" name="livraison" value="0"
                        {{ old('livraison') == '0' ? 'checked' : '' }}>
                    <label for="livraison_non">Non</label>
                </div>
                @error('livraison')
                    <div class="error">{{ $message }}</div>
                @enderror

                <!-- À emporter -->
                <label for="aemporter">À emporter :</label>
                <div class="d-inline-flex pl-3">
                    <input type="radio" class="mx-2" id="aemporter_oui" name="aemporter" value="1"
                        {{ old('aemporter') == '1' ? 'checked' : '' }}>
                    <label for="aemporter_oui">Oui</label>

                    <input type="radio" class="mx-2" id="aemporter_non" name="aemporter" value="0"
                        {{ old('aemporter') == '0' ? 'checked' : '' }}>
                    <label for="aemporter_non">Non</label>
                </div>
                @error('aemporter')
                    <div class="error">{{ $message }}</div>
                @enderror

                <!-- Description -->
                <label for="description">Description :</label>
                <textarea id="description" name="description" placeholder="Entrez une description (optionnel)">{{ old('description') }}</textarea>
                @error('description')
                    <div class="error">{{ $message }}</div>
                @enderror

                <!-- Image -->
                <label for="imageetablissement">Insérer une bannière :</label>
                <input type="file" id="imageetablissement" name="imageetablissement" accept="image/*">
                @error('imageetablissement')
                    <div class="error">{{ $message }}</div>
                @enderror

                <!-- Horaires -->
                <label for="horaire_ouverture">Horaires d'ouverture :</label>
                <input type="time" id="horaire_ouverture" name="horaire_ouverture" required
                    value="{{ old('horaire_ouverture') }}">
                @error('horaire_ouverture')
                    <div class="error">{{ $message }}</div>
                @enderror

                <label for="horaire_fermeture">Horaires de fermeture :</label>
                <input type="time" id="horaire_fermeture" name="horaire_fermeture" required
                    value="{{ old('horaire_fermeture') }}">
                @error('horaire_fermeture')
                    <div class="error">{{ $message }}</div>
                @enderror

                <!-- Bouton de soumission -->
                <div class="d-flex justify-content-center">
                    <button class="btn-add" type="submit">Créer</button>
                </div>
            </form>
        </div>

    </div>

    <div class="d-flex justify-content-end">
        <img role="presentation" src="{{ asset('img/burger.png') }}" alt="Burger">
    </div>

@endsection
