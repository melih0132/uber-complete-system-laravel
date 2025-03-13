@extends('layouts.app')

@section('title', 'Ajouter votre établissement')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/ajout-restaurant.blade.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
@endsection

@section('content')

    <div class="container">
        <div class="add-form">
            <h1>Créer votre établissement</h1>

            <!-- Formulaire principal -->
            <form action="{{ route('etablissement.store') }}" method="POST">
                @csrf

                <!-- Zone : Informations Générales -->
                <div class="zone">
                    <div class="zone-title">Informations Générales</div>
                    <div class="zone-content">
                        <div class="form-group">
                            <label for="nometablissement">Nom de l'établissement :</label>
                            <input type="text" id="nometablissement" name="nometablissement" required
                                placeholder="Entrez le nom de l'établissement" value="{{ old('nometablissement') }}">
                            @error('nometablissement')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="typeetablissement">Type d'établissement :</label>
                            <select name="typeetablissement" id="typeetablissement" required>
                                <option value="" disabled selected>Sélectionnez le type d'établissement</option>
                                <option value="Restaurant" {{ old('typeetablissement') == 'Restaurant' ? 'selected' : '' }}>
                                    Restaurant
                                </option>
                                <option value="Épicerie" {{ old('typeetablissement') == 'Épicerie' ? 'selected' : '' }}>
                                    Épicerie
                                </option>
                            </select>
                            @error('typeetablissement')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Zone : Adresse -->
                <div class="zone">
                    <div class="zone-title">Adresse</div>
                    <div class="zone-content">
                        <div class="form-group">
                            <label for="libelleadresse">Adresse :</label>
                            <input type="text" id="libelleadresse" name="libelleadresse" required
                                placeholder="Entrez l'adresse de l'établissement" value="{{ old('libelleadresse') }}">
                            @error('libelleadresse')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="nomville">Ville :</label>
                            <input type="text" id="nomville" name="nomville" required
                                placeholder="Entrez le nom de la ville" value="{{ old('nomville') }}">
                            @error('nomville')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="codepostal">Code Postal :</label>
                            <input type="text" id="codepostal" name="codepostal" required
                                placeholder="Entrez le code postal" value="{{ old('codepostal') }}">
                            @error('codepostal')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Zone : Options -->
                <div class="zone">
                    <div class="zone-title">Options</div>
                    <div class="zone-content">
                        <div class="form-group">
                            <label for="livraison">Livraison :</label>
                            <div class="radio-group">
                                <input type="radio" id="livraison_oui" name="livraison" value="1"
                                    {{ old('livraison') == '1' ? 'checked' : '' }}>
                                <label for="livraison_oui">Oui</label>
                                <input type="radio" id="livraison_non" name="livraison" value="0"
                                    {{ old('livraison') == '0' ? 'checked' : '' }}>
                                <label for="livraison_non">Non</label>
                            </div>
                            @error('livraison')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="aemporter">À emporter :</label>
                            <div class="radio-group">
                                <input type="radio" id="aemporter_oui" name="aemporter" value="1"
                                    {{ old('aemporter') == '1' ? 'checked' : '' }}>
                                <label for="aemporter_oui">Oui</label>

                                <input type="radio" id="aemporter_non" name="aemporter" value="0"
                                    {{ old('aemporter') == '0' ? 'checked' : '' }}>
                                <label for="aemporter_non">Non</label>
                            </div>
                            @error('aemporter')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Zone : Catégories -->
                <div class="zone">
                    <div class="zone-title">Catégories</div>
                    <div class="form-group">
                        <label for="categories">Catégories de prestations :</label>
                        <select name="categories[]" id="categories" class="form-control" multiple required>
                            @foreach ($categories as $categorie)
                                <option value="{{ $categorie->idcategorieprestation }}"
                                    {{ is_array(old('categories')) && in_array($categorie->idcategorieprestation, old('categories')) ? 'selected' : '' }}>
                                    {{ $categorie->libellecategorieprestation }}
                                </option>
                            @endforeach
                        </select>
                        @error('categories')
                            <div class="error">{{ $message }}</div>
                        @enderror
                        <div class="error" id="categories-error" style="display: none;">Veuillez sélectionner au moins 3
                            catégories.</div>
                    </div>
                </div>

                <!-- Zone : Description -->
                <div class="zone">
                    <div class="zone-title">Description</div>
                    <div class="form-group">
                        <label for="description">Description :</label>
                        <textarea id="description" name="description" placeholder="Entrez une description">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Zone : Horaires -->
                <div class="zone">
                    <div class="zone-title">Horaires</div>
                    <div class="horaires">
                        <div class="header jour">Jour</div>
                        <div class="header">Ouverture</div>
                        <div class="header">Fermeture</div>
                        <div class="header">Fermé</div>

                        @foreach (['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'] as $jour)
                            <div class="jour">{{ ucfirst($jour) }}</div>
                            <div>
                                <input type="time" id="horairesouverture_{{ $jour }}"
                                    name="horairesouverture[{{ $jour }}]"
                                    value="{{ old('horairesouverture.' . $jour) }}" class="horaires-field"
                                    {{ old('ferme.' . $jour) ? 'disabled' : '' }} required>
                            </div>
                            <div>
                                <input type="time" id="horairesfermeture_{{ $jour }}"
                                    name="horairesfermeture[{{ $jour }}]"
                                    value="{{ old('horairesfermeture.' . $jour) }}" class="horaires-field"
                                    {{ old('ferme.' . $jour) ? 'disabled' : '' }} required>
                            </div>
                            <div>
                                <input type="checkbox" id="ferme_{{ $jour }}" name="ferme[{{ $jour }}]"
                                    value="1" onclick="toggleFerme('{{ $jour }}')"
                                    {{ old('ferme.' . $jour) ? 'checked' : '' }}>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Bouton -->
                <div class="d-flex justify-content-center">
                    <button class="btn-add" type="submit">Ajouter une banière</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#categories').select2({
                placeholder: "Sélectionnez au moins 3 catégories",
                allowClear: true
            });
        });

        function validateCategories() {
            const selectedCategories = $('#categories').val();
            const error = document.getElementById('categories-error');
            if (!selectedCategories || selectedCategories.length < 3) {
                error.style.display = 'block';
                return false;
            } else {
                error.style.display = 'none';
                return true;
            }
        }

        function toggleFerme(jour) {
            const checkbox = document.getElementById(`ferme_${jour}`);
            const ouverture = document.getElementById(`horairesouverture_${jour}`);
            const fermeture = document.getElementById(`horairesfermeture_${jour}`);

            if (checkbox.checked) {
                ouverture.value = "";
                fermeture.value = "";
                ouverture.disabled = true;
                fermeture.disabled = true;
                ouverture.removeAttribute("required");
                fermeture.removeAttribute("required");
            } else {
                ouverture.disabled = false;
                fermeture.disabled = false;
                ouverture.setAttribute("required", "required");
                fermeture.setAttribute("required", "required");
            }
        }

        function handleHoraireInput(jour) {
            const checkbox = document.getElementById(`ferme_${jour}`);
            const ouverture = document.getElementById(`horairesouverture_${jour}`);
            const fermeture = document.getElementById(`horairesfermeture_${jour}`);

            if (ouverture.value || fermeture.value) {
                checkbox.checked = false;
                checkbox.disabled = true;
            } else {
                checkbox.disabled = false;
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'].forEach(jour => {
                const checkbox = document.getElementById(`ferme_${jour}`);
                if (checkbox && checkbox.checked) {
                    toggleFerme(jour);
                }
            });
        });
    </script>

@endsection
