@extends('layouts.app')

@section('title', 'Demandes de Modification')

@section('content')
    <div class="container">
        <h1 class="mb-4">Demandes de Modification</h1>

        {{-- Messages de succès et d'erreur --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        {{-- Table des modifications --}}
        @if (!empty($modifications) && count($modifications) > 0)
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Coursier</th>
                        <th>Modèle du Véhicule</th>
                        <th>Demande</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($modifications as $index => $modification)
                        <tr>
                            <td>{{ $modification['coursier'] ?? 'Non spécifié' }}</td>
                            <td>{{ $modification['modele'] ?? 'Non spécifié' }}</td>
                            <td>{{ $modification['demande'] ?? 'Non spécifié' }}</td>
                            <td>{{ $modification['date'] ?? 'Non spécifié' }}</td>
                            <td>
                                <form method="POST" action="{{ route('modifications.supprimer', $index) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="alert alert-info" role="alert">
                Aucune demande de modification n'a été enregistrée pour le moment.
            </div>
        @endif

        {{-- Bouton Retour --}}
        <div class="mt-3">
            <a href="{{ route('logistique.vehicules') }}" class="btn btn-primary">Retour</a>
        </div>
    </div>
@endsection
