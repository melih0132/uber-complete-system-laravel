@extends('layouts.app')

@section('title', 'Ajouter un produit')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/produit.css') }}">
@endsection

@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-4">Ajouter un Produit</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('manager.produits.store') }}" method="POST" enctype="multipart/form-data"
            class="shadow p-4 bg-white rounded">
            @csrf

            <div class="mb-3">
                <label for="idetablissement" class="form-label">Établissement :</label>
                <select name="idetablissement" id="idetablissement" class="form-select" required>
                    <option value="" disabled selected>-- Sélectionnez un établissement --</option>
                    @foreach ($etablissements as $etablissement)
                        <option value="{{ $etablissement->idetablissement }}">{{ $etablissement->nometablissement }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="nomproduit" class="form-label">Nom du produit :</label>
                <input type="text" id="nomproduit" name="nomproduit" value="{{ old('nomproduit') }}" class="form-control"
                    placeholder="Entrez le nom du produit" required>
            </div>

            <div class="mb-3">
                <label for="prixproduit" class="form-label">Prix :</label>
                <input type="number" step="0.01" id="prixproduit" name="prixproduit" value="{{ old('prixproduit') }}"
                    class="form-control" placeholder="Entrez le prix du produit" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description :</label>
                <textarea id="description" name="description" class="form-control" rows="4" placeholder="Entrez une description">{{ old('description') }}</textarea>
            </div>

            <div class="mb-3">
                <label for="idcategorie" class="form-label">Catégorie :</label>
                <select name="idcategorie" id="idcategorie" class="form-select" required>
                    <option value="" disabled selected>-- Sélectionnez une catégorie --</option>
                    @foreach ($categories as $categorie)
                        <option value="{{ $categorie->idcategorie }}">{{ $categorie->nomcategorie }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="imageproduit" class="form-label">Image du produit :</label>
                <div id="drop-zone" class="drop-zone">
                    <span>Glissez-déposez une image ici ou cliquez pour sélectionner</span>
                    <input type="file" id="imageproduit" name="imageproduit" accept="image/*" class="form-control">
                </div>
                <div id="preview" class="preview">
                    <p>Aucune image sélectionnée</p>
                </div>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary">Ajouter le produit</button>
            </div>
        </form>
    </div>
@endsection


@section('js')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const dropZone = document.getElementById("drop-zone");
            const inputFile = document.getElementById("imageproduit");
            const preview = document.getElementById("preview");

            // Fonction pour afficher l'image
            function previewImage(file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" alt="Prévisualisation">`;
                };
                reader.readAsDataURL(file);
            }

            // Gestion du clic sur le drop-zone
            dropZone.addEventListener("click", () => {
                inputFile.click();
            });

            // Gestion de l'ajout d'un fichier via l'input
            inputFile.addEventListener("change", (e) => {
                const file = e.target.files[0];
                if (file) {
                    previewImage(file);
                }
            });

            // Gestion du drag-and-drop
            dropZone.addEventListener("dragover", (e) => {
                e.preventDefault();
                dropZone.classList.add("dragover");
            });

            dropZone.addEventListener("dragleave", () => {
                dropZone.classList.remove("dragover");
            });

            dropZone.addEventListener("drop", (e) => {
                e.preventDefault();
                dropZone.classList.remove("dragover");
                const file = e.dataTransfer.files[0];
                if (file) {
                    inputFile.files = e.dataTransfer.files;
                    previewImage(file);
                }
            });
        });
    </script>
@endsection
