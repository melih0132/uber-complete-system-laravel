@extends('layouts.app')

@section('title', 'Gestion des Véhicules')

@section('content')
    <div class="container">
        <h1 class="mb-4">Gestion des Véhicules pour les Coursiers Retenus</h1>

        {{-- Messages de succès et d'erreur --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Tableau des coursiers --}}
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID Coursier</th>
                    <th>Nom Coursier</th>
                    <th>Véhicule</th>
                    <th>Statut Véhicule</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($coursiers as $coursier)
                    <tr>
                        <td>{{ $coursier->idcoursier }}</td>
                        <td>{{ $coursier->nomuser }}</td>
                        <td>{{ $coursier->vehicule->modele ?? 'Non attribué' }}</td>
                        <td>
                            @if ($coursier->vehicule)
                                @switch($coursier->vehicule->statusprocessuslogistique)
                                    @case('En attente')
                                        <span class="badge bg-warning text-dark">En attente</span>
                                        @break
                                    @case('Validé')
                                        <span class="badge bg-success">Validé</span>
                                        @break
                                    @case('Refusé')
                                        <span class="badge bg-danger">Refusé</span>
                                        @break
                                    @case('Modifications demandées')
                                        <span class="badge bg-primary">Modifications demandées</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">Inconnu</span>
                                @endswitch
                            @else
                                <span class="badge bg-secondary">Non attribué</span>
                            @endif
                        </td>
                        <td>
                            @if (optional($coursier->vehicule)->statusprocessuslogistique === 'En attente')
                                <form action="{{ route('logistique.vehicules.valider', $coursier->idcoursier) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">Valider</button>
                                </form>

                                <form action="{{ route('logistique.vehicules.refuser', $coursier->idcoursier) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm">Refuser</button>
                                </form>

                                <a href="{{ route('logistique.vehicules.modifierForm', $coursier->idcoursier) }}" class="btn btn-warning btn-sm">Demander modification</a>
                            @elseif (optional($coursier->vehicule)->statusprocessuslogistique === 'Validé')
                                <span>Aucune action nécessaire</span>
                            @elseif (optional($coursier->vehicule)->statusprocessuslogistique === 'Refusé')
                                <span>Véhicule refusé</span>
                            @elseif (optional($coursier->vehicule)->statusprocessuslogistique === 'Modifications demandées')
                                <span>Modifications en cours</span>
                            @else
                                <span>Aucune action disponible</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
