@extends('layouts.app')

@section('title', 'Inscription Service')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth.blade.css') }}">
@endsection

@section('content')
    <div class="container">
        <div class="d-flex justify-content-center mt-5 card px-3">
            <h1>Inscription Service</h1>
            <form action="{{ route('register') }}" method="POST" class="form-register d-flex flex-column justify-content-center">
                @csrf
                <input type="hidden" name="role" value="service">

                <div class="form-group">
                    <label for="nomservice">Nom du service :</label>
                    <input type="text" name="nomservice" id="nomservice" class="form-control" required maxlength="100">
                </div>

                <div class="form-group">
                    <label for="description">Description du service :</label>
                    <textarea name="description" id="description" class="form-control" required rows="4"></textarea>
                </div>

                <div class="form-group">
                    <label for="contactperson">Nom de la personne contact :</label>
                    <input type="text" name="contactperson" id="contactperson" class="form-control" required maxlength="100">
                </div>

                <div class="form-group">
                    <label for="telephone">Téléphone :</label>
                    <input type="text" name="telephone" id="telephone" class="form-control" required
                        pattern="^(06|07)[0-9]{8}$"
                        title="Numéro de téléphone valide (06 ou 07 suivi de 8 chiffres)">
                </div>

                <div class="form-group">
                    <label for="emailservice">Email du service :</label>
                    <input type="email" name="emailservice" id="emailservice" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="adresse">Adresse du service :</label>
                    <input type="text" name="adresse" id="adresse" class="form-control" required maxlength="100">
                </div>

                <div class="form-group">
                    <label for="ville">Ville :</label>
                    <input type="text" name="ville" id="ville" class="form-control" required maxlength="50">
                </div>

                <div class="form-group">
                    <label for="codepostal">Code Postal :</label>
                    <input type="text" name="codepostal" id="codepostal" class="form-control"
                        title="Code postal valide (5 chiffres)" required>
                </div>

                <button type="submit" class="btn-login">S'inscrire</button>
            </form>
        </div>
    </div>
@endsection
