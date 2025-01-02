@extends('layouts.app')

@section('title', 'Inscription Responsable d\'Enseigne')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth.blade.css') }}">
@endsection

@section('js')
    <script src="{{ asset('js/js.js') }}"></script>
@endsection

@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-4">Inscription Responsable d'Enseigne</h1>

        <form action="{{ route('register') }}" method="POST" class="form-register d-flex flex-column justify-content-center">
            @csrf
            <div class="form-group mb-3">
                <label for="nomuser">Nom du Responsable :</label>
                <input type="text" name="nomuser" id="nomuser" class="form-control" required maxlength="50"
                    placeholder="Nom du responsable">
            </div>

            <div class="form-group mb-3">
                <label for="prenomuser">Prénom du Responsable :</label>
                <input type="text" name="prenomuser" id="prenomuser" class="form-control" required maxlength="50"
                    placeholder="Prénom du responsable">
            </div>

            <div class="form-group mb-3">
                <label for="telephone">Téléphone :</label>
                <input type="text" name="telephone" id="telephone" class="form-control" required
                    pattern="^(06|07)[0-9]{8}$|^\+?[1-9][0-9]{1,14}$"
                    title="Numéro de téléphone valide (06 ou 07 suivi de 8 chiffres ou format international)"
                    placeholder="06XXXXXXXX" inputmode="tel" oninput="validatePhoneNumberInput(this)">
                <small>Exemple : 0612345678 ou +33123456789</small>
            </div>

            <div class="form-group mb-3">
                <label for="emailuser">Email du Responsable :</label>
                <input type="email" name="emailuser" id="emailuser" class="form-control" required maxlength="200"
                    placeholder="Email professionnel">
            </div>

            <div class="form-group mb-3">
                <label for="motdepasseuser">Mot de passe :</label>
                <input type="password" id="motdepasseuser" name="motdepasseuser" class="form-control" required
                    minlength="8" placeholder="Mot de passe sécurisé" oninput="checkPasswordStrength()">
                <div id="password-strength" class="mt-2" style="font-weight: bold;"></div>
            </div>

            <div class="form-group mb-3">
                <label for="motdepasseuser_confirmation">Confirmation du mot de passe :</label>
                <input type="password" name="motdepasseuser_confirmation" id="motdepasseuser_confirmation"
                    class="form-control" required placeholder="Confirmez votre mot de passe">
            </div>

            @if (session('success') || session('error'))
                <div class="alert-message @if (session('success')) success @elseif(session('error')) error @endif"
                    role="alert">
                    {{ session('success') ?? session('error') }}
                </div>
            @endif

            {{-- Ne surtout pas enlever --}}
            <input type="hidden" name="role" value="responsable">

            <button type="submit" class="btn-login mt-4">S'inscrire</button>
        </form>
    </div>

@endsection
