@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Gestion des validations administratives</h1>

        <!-- Formulaire d'initialisation de validation -->
        <form action="{{ route('admin.validation.initier') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="idcoursier" class="form-label">ID Coursier</label>
                <input type="number" class="form-control" id="idcoursier" name="idcoursier" required>
            </div>
            <div class="mb-3">
                <label for="iban" class="form-label">IBAN</label>
                <input type="text" class="form-control" id="iban" name="iban" required>
            </div>
            <div class="mb-3">
                <label for="datedebutactivite" class="form-label">Date de début d'activité</label>
                <input type="date" class="form-control" id="datedebutactivite" name="datedebutactivite" required>
            </div>
            <button type="submit" class="btn btn-primary">Initier Validation</button>
        </form>

        <!-- Formulaire de relance -->
        <form action="{{ route('admin.validation.relancer', ['idcoursier' => '']) }}" method="POST" id="relanceForm">
            @csrf
            <div class="mb-3">
                <label for="relance_idcoursier" class="form-label">ID Coursier</label>
                <input type="number" class="form-control" id="relance_idcoursier" name="idcoursier" required>
            </div>
            <button type="submit" class="btn btn-warning">Relancer</button>
        </form>

        <!-- Formulaire de suppression -->
        <form action="{{ route('admin.validation.supprimer', ['idcoursier' => '']) }}" method="POST" id="supprimerForm">
            @csrf
            @method('DELETE')
            <div class="mb-3">
                <label for="supprimer_idcoursier" class="form-label">ID Coursier</label>
                <input type="number" class="form-control" id="supprimer_idcoursier" name="idcoursier" required>
            </div>
            <button type="submit" class="btn btn-danger">Supprimer</button>
        </form>
    </div>
@endsection
