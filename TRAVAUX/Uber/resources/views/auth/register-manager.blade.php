@extends('layouts.app')

@section('title', 'Inscription Responsable d\'Enseigne')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth.blade.css') }}">
@endsection

@section('content')
    <div class="container mt-5">
        <h1 class="text-center">Inscription Responsable d'Enseigne (à compléter et faire marcher)</h1>

        <form action="{{ route('register') }}" method="POST" class="form-register d-flex flex-column justify-content-center">
            @csrf
            <div class="form-group">
                <label for="nomservice">Nom de l'Établissement :</label>
                <input type="text" name="nomservice" id="nomservice" class="form-control" required maxlength="100"
                    placeholder="Nom de votre restaurant ou service">
            </div>

            <div class="form-group">
                <label for="description">Description de l'Établissement :</label>
                <textarea name="description" id="description" class="form-control" required rows="4"
                    placeholder="Décrivez brièvement votre établissement, spécialités, etc."></textarea>
            </div>

            <div class="form-group">
                <label for="categorie">Catégorie de l'Établissement :</label>
                <select name="categorie" id="categorie" class="form-control" required>
                    <option value="" disabled selected>Choisissez une catégorie</option>
                    <option value="restaurant">Fast-Food</option>
                    <option value="boulangerie">Italien</option>
                    <option value="boulangerie">autre ...</option>
                </select>
            </div>

            <div class="form-group">
                <label for="contactperson">Nom de la Personne de Contact :</label>
                <input type="text" name="contactperson" id="contactperson" class="form-control" required maxlength="100"
                    placeholder="Nom complet">
            </div>

            <div class="form-group">
                <label for="telephone">Téléphone :</label>
                <input type="text" name="telephone" id="telephone" class="form-control" required
                    pattern="^(06|07)[0-9]{8}$" title="Numéro de téléphone valide (06 ou 07 suivi de 8 chiffres)"
                    placeholder="06XXXXXXXX">
            </div>

            <div class="form-group">
                <label for="emailservice">Email du Service :</label>
                <input type="email" name="emailservice" id="emailservice" class="form-control" required
                    placeholder="Email professionnel">
            </div>

            <div class="form-group">
                <label for="adresse">Adresse de l'Établissement :</label>
                <input type="text" name="adresse" id="adresse" class="form-control" required maxlength="100"
                    placeholder="Rue, numéro">
            </div>

            <div class="form-group">
                <label for="ville">Ville :</label>
                <input type="text" name="ville" id="ville" class="form-control" required maxlength="50"
                    placeholder="Ville">
            </div>

            <div class="form-group">
                <label for="codepostal">Code Postal :</label>
                <input type="text" name="codepostal" id="codepostal" class="form-control" required pattern="^[0-9]{5}$"
                    title="Code postal valide (5 chiffres)" placeholder="Code postal">
            </div>

            {{-- NE SURTOUT PAS ENLEVER --}}
            <input type="hidden" name="role" value="manager">

            <button type="submit" class="btn-login mt-4">S'inscrire</button>
        </form>
    </div>
@endsection
