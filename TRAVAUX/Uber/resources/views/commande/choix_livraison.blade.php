@extends('layouts.ubereats')

@section('title', 'Choix Commande')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/panier.blade.css') }}">
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

    <div class="container">
        <h1>Choisissez votre mode de livraison</h1>
        <form method="POST" action="{{ route('commande.choixLivraisonStore') }}">
            @csrf
            <div class="radio-group">
                <label>
                    <input type="radio" name="modeLivraison" value="livraison"
                        {{ old('modeLivraison') == 'livraison' ? 'checked' : '' }} required>
                    Livraison à domicile
                </label>
                <label>
                    <input type="radio" name="modeLivraison" value="retrait"
                        {{ old('modeLivraison') == 'retrait' ? 'checked' : '' }}>
                    Retrait sur place
                </label>
            </div>

            <!-- Champ d'adresse de livraison -->
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

            <button type="submit" class="btn-panier">Continuer</button>
        </form>
    </div>

@endsection

@section('js')
    <script>
        // Script pour afficher ou masquer le champ d'adresse, ville et code postal en fonction du choix
        document.addEventListener('DOMContentLoaded', function() {
            const modeLivraisonInputs = document.querySelectorAll('input[name="modeLivraison"]');
            const adresseLivraisonContainer = document.getElementById('adresseLivraisonContainer');

            function toggleAdresseLivraison() {
                const selectedMode = document.querySelector('input[name="modeLivraison"]:checked').value;
                if (selectedMode === 'livraison') {
                    adresseLivraisonContainer.style.display = 'block';
                } else {
                    adresseLivraisonContainer.style.display = 'none';
                }
            }

            modeLivraisonInputs.forEach(input => {
                input.addEventListener('change', toggleAdresseLivraison);
            });

            // Initialisation : Vérifier si "livraison" est déjà sélectionné
            toggleAdresseLivraison();
        });
    </script>
@endsection
