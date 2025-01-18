@extends('layouts.ubereats')

@section('title', 'Choix Commande')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/panier.blade.css') }}">
@endsection

@section('content')
    {{-- Alertes de succès ou d'erreur --}}
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

    <div class="container">
        <h1>Choisissez votre mode de livraison</h1>
        <form method="POST" action="{{ route('commande.choixLivraisonStore') }}">
            @csrf

            {{-- Choix du mode de livraison --}}
            <div class="radio-group mb-4">
                <label>
                    <input type="radio" name="modeLivraison" value="livraison"
                        {{ old('modeLivraison') === 'livraison' ? 'checked' : '' }} required>
                    Livraison à domicile
                </label>
                <label>
                    <input type="radio" name="modeLivraison" value="retrait"
                        {{ old('modeLivraison') === 'retrait' ? 'checked' : '' }}>
                    Retrait sur place
                </label>
            </div>

            {{-- Champs pour l'adresse de livraison (affichés uniquement si "livraison") --}}
            <div id="adresseLivraisonContainer" class="my-3" style="display: none;">
                <label for="adresse_livraison">Adresse de livraison :</label>
                <input type="text" id="adresse_livraison" name="adresse_livraison" class="form-control"
                    placeholder="Entrez votre adresse" value="{{ old('adresse_livraison') }}">

                <label for="ville">Ville :</label>
                <input type="text" id="ville" name="ville" class="form-control" placeholder="Entrez votre ville"
                    value="{{ old('ville') }}">

                <label for="code_postal">Code Postal :</label>
                <input type="text" id="code_postal" name="code_postal" class="form-control"
                    placeholder="Entrez votre code postal" value="{{ old('code_postal') }}">
            </div>

            {{-- Bouton de soumission --}}
            <button type="submit" class="btn-panier">Continuer</button>
        </form>
    </div>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modeLivraisonInputs = document.querySelectorAll('input[name="modeLivraison"]');
            const adresseLivraisonContainer = document.getElementById('adresseLivraisonContainer');
            const addressFields = ['adresse_livraison', 'ville', 'code_postal'];

            function toggleAdresseLivraison() {
                const selectedMode = document.querySelector('input[name="modeLivraison"]:checked').value;
                const isDelivery = selectedMode === 'livraison';

                adresseLivraisonContainer.style.display = isDelivery ? 'block' : 'none';
                addressFields.forEach(fieldId => {
                    document.getElementById(fieldId).required = isDelivery;
                });
            }

            modeLivraisonInputs.forEach(input => {
                input.addEventListener('change', toggleAdresseLivraison);
            });

            toggleAdresseLivraison(); // Initialise l'état au chargement
        });
    </script>
@endsection
