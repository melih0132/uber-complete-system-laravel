@extends('layouts.ubereats')

@section('title', 'Ajouter un produit')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/ajout-restaurant.blade.css') }}">
@endsection

@section('content')

    <div class="container">
        <div class="add-form my-5">
            <h1>Ajouter un produit</h1>

            <form action="{{ route('produit.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Nom du produit -->
                <label for="nomproduit">Nom du produit :</label>
                <input type="text" id="nomproduit" name="nomproduit" required
                    placeholder="Entrez le nom du produit" value="{{ old('nomproduit') }}">
                @error('nomproduit')
                    <div class="error">{{ $message }}</div>
                @enderror

                <!-- Prix du produit -->
                <label for="prixproduit">Prix du produit :</label>
                <input type="number" id="prixproduit" name="prixproduit" required
                    placeholder="Entrez le prix du produit" step="0.01" value="{{ old('prixproduit') }}">
                @error('prixproduit')
                    <div class="error">{{ $message }}</div>
                @enderror

                <!-- Description du produit -->
                <label for="descriptionproduit">Description :</label>
                <textarea id="descriptionproduit" name="descriptionproduit" placeholder="Entrez une description (optionnel)">{{ old('descriptionproduit') }}</textarea>
                @error('descriptionproduit')
                    <div class="error">{{ $message }}</div>
                @enderror

                <!-- Image du produit -->
                <label for="imageproduit">Ajouter une image pour le produit :</label>
                <input type="file" id="imageproduit" name="imageproduit" required accept="image/*">
                @error('imageproduit')
                    <div class="error">{{ $message }}</div>
                @enderror

                <div class="d-flex justify-content-center">
                    <button class="btn-add" type="submit">Ajouter</button>
                </div>

            </form>
        </div>
    </div>

@endsection
