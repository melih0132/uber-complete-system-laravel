@extends('layouts.app')

@section('title', "Demande d'aménagement(s)")

@section('css')
    <link rel="stylesheet" href="{{ asset('css/entretien.blade.css') }}">
@endsection

@section('content')
    <div class="container mt-5">
        <h1 class="mb-4">Demande d'aménagement(s)</h1>

        @if ($vehicules->isNotEmpty())
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Coursier</th>
                        <th>Modèle du Véhicule</th>
                        <th>Demande</th>
                        <th>État</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($vehicules as $vehicule)
                        <tr class="table-bordered">
                            <td>{{ $vehicule->coursier->nomuser ?? 'Nom non spécifié' }}
                                {{ $vehicule->coursier->prenomuser ?? 'Prénom non spécifié' }}</td>
                            <td> {{ $vehicule->marque }}
                                {{ $vehicule->modele }} ({{ $vehicule->immatriculation }})</td>
                            <td>{{ $vehicule->demandemodification ?? 'Non spécifiée' }}</td>
                            <td>
                                @if ($vehicule->demandemodificationeffectue)
                                    <span class="badge bg-success">Effectuée</span>
                                @else
                                    <span class="badge bg-warning">En attente</span>
                                @endif
                            </td>
                            <td>
                                <form method="POST" action="{{ route('modifications.supprimer', $vehicule->idvehicule) }}"
                                    class="d-inline">
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
            <div>
                Aucune demande de modification n'a été enregistrée pour le moment.
            </div>
        @endif

        <div class="mt-3">
            <a href="{{ route('logistique.vehicules') }}" class="btn-entretien text-decoration-none">Retour</a>
        </div>
    </div>
@endsection
