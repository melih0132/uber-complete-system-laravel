@extends('layouts.app')

@section('title', 'Création fiche véhicule')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/entretien.blade.css') }}">
@endsection

@section('content')
    <div class="container mt-5">
        <h1 class="mb-4">Enregistrer un véhicule</h1>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-3">
            <h4>Attribué à :</h4>
            <p><strong>{{ $coursier->prenomuser }} {{ $coursier->nomuser }}</strong> (ID: {{ $coursier->idcoursier }})</p>
        </div>

        <form action="{{ route('logistique.vehicules.store') }}" method="POST" class="mt-4">
            @csrf

            <input type="hidden" name="idcoursier" value="{{ $coursier->idcoursier }}">

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="immatriculation" class="form-label">Immatriculation</label>
                    <input type="text" name="immatriculation" id="immatriculation" class="form-control" required
                        placeholder="XX-000-XX" value="{{ old('immatriculation') }}">
                </div>
                <div class="col-md-6">
                    <label for="marque" class="form-label">Marque</label>
                    <input type="text" name="marque" id="marque" class="form-control" required
                        placeholder="Ex: Toyota" value="{{ old('marque') }}">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="modele" class="form-label">Modèle</label>
                    <input type="text" name="modele" id="modele" class="form-control" required
                        placeholder="Ex: Corolla" value="{{ old('modele') }}">
                </div>
                <div class="col-md-6">
                    <label for="capacite" class="form-label">Capacité</label>
                    <input type="number" name="capacite" id="capacite" class="form-control" required
                        placeholder="Nombre de places (2-7)" value="{{ old('capacite') }}" min="2" max="7">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="couleur" class="form-label">Couleur</label>
                    <input type="text" name="couleur" id="couleur" class="form-control" placeholder="Ex: Bleu" required
                        value="{{ old('couleur') }}">
                </div>
                <div class="col-md-6">
                    <label for="accepteanimaux" class="form-label">Accepte Animaux</label>
                    <select name="accepteanimaux" id="accepteanimaux" class="form-select" required>
                        <option value="1" {{ old('accepteanimaux') == '1' ? 'selected' : '' }}>Oui</option>
                        <option value="0" {{ old('accepteanimaux') == '0' ? 'selected' : '' }}>Non</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="estelectrique" class="form-label">Est Électrique</label>
                    <select name="estelectrique" id="estelectrique" class="form-select" required>
                        <option value="1" {{ old('estelectrique') == '1' ? 'selected' : '' }}>Oui</option>
                        <option value="0" {{ old('estelectrique') == '0' ? 'selected' : '' }}>Non</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="estconfortable" class="form-label">Est Confortable</label>
                    <select name="estconfortable" id="estconfortable" class="form-select" required>
                        <option value="1" {{ old('estconfortable') == '1' ? 'selected' : '' }}>Oui</option>
                        <option value="0" {{ old('estconfortable') == '0' ? 'selected' : '' }}>Non</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="estrecent" class="form-label">Est Récent</label>
                    <select name="estrecent" id="estrecent" class="form-select" required>
                        <option value="1" {{ old('estrecent') == '1' ? 'selected' : '' }}>Oui</option>
                        <option value="0" {{ old('estrecent') == '0' ? 'selected' : '' }}>Non</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="estluxueux" class="form-label">Est Luxueux</label>
                    <select name="estluxueux" id="estluxueux" class="form-select" required>
                        <option value="1" {{ old('estluxueux') == '1' ? 'selected' : '' }}>Oui</option>
                        <option value="0" {{ old('estluxueux') == '0' ? 'selected' : '' }}>Non</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn-entretien">Enregistrer</button>
        </form>
    </div>
@endsection
