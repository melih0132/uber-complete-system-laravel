@extends('layouts.app')

@section('title', 'Ajouter votre carte bancaire')

@section('css')
    <!-- Include the external CSS file -->
    <link rel="stylesheet" href="{{ asset('css/carte-bancaire.blade.css') }}">
@endsection

@section('content')
    <div class="form-container">
        <h2>Ajout d'une carte bancaire</h2>

        <!-- Global Errors -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul style="list-style: none; padding-left:0;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <form action="{{ route('carte_bancaire.store') }}" method="POST">
            @csrf

            <!-- Card Number Field -->
            <div>
                <label for="numerocb">Numéro de la carte</label>
                <input type="text" id="numerocb" name="numerocb" value="{{ old('numerocb') }}"
                    class="@error('numerocb') input-error @enderror" placeholder="Entrez le numéro de carte" maxlength="16">
            </div>

            <!-- Expiration Date Field -->
            <div>
                <label for="dateexpirecb">Date d'expiration</label>
                <input type="date" id="dateexpirecb" name="dateexpirecb" value="{{ old('dateexpirecb') }}"
                    class="@error('dateexpirecb') input-error @enderror">
            </div>

            <!-- Cryptogram Field -->
            <div>
                <label for="cryptogramme">Cryptogramme (3 chiffres)</label>
                <input type="text" id="cryptogramme" name="cryptogramme" value="{{ old('cryptogramme') }}"
                    class="@error('cryptogramme') input-error @enderror" placeholder="xxx" maxlength="3">
            </div>

            <!-- Card Type Field -->
            <div>
                <label for="typecarte">Type de carte</label>
                <select id="typecarte" name="typecarte" class="@error('typecarte') input-error @enderror">
                    <option value="Crédit" {{ old('typecarte') == 'Crédit' ? 'selected' : '' }}>Crédit</option>
                    <option value="Débit" {{ old('typecarte') == 'Débit' ? 'selected' : '' }}>Débit</option>
                </select>
            </div>

            <!-- Network Type Field -->
            <div>
                <label for="typereseaux">Type de réseau</label>
                <select id="typereseaux" name="typereseaux" class="@error('typereseaux') input-error @enderror">
                    <option value="Visa" {{ old('typereseaux') == 'Visa' ? 'selected' : '' }}>Visa</option>
                    <option value="MasterCard" {{ old('typereseaux') == 'MasterCard' ? 'selected' : '' }}>MasterCard
                    </option>
                </select>
            </div>

            <!-- Submit Button -->
            <button class="btn" type="submit">Ajouter la carte</button>
        </form>
    </div>

    @if (session('success'))
        <div class="success-container">
            <p>🎉 Carte ajoutée avec succès : {{ session('success') }}</p>
        </div>
    @endif

@endsection
