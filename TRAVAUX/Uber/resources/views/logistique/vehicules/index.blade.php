@extends('layouts.app')

@section('title', 'Validation des Véhicules')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/entretien.blade.css') }}">
@endsection

@section('content')
    <div class="container mt-5">
        <h1 class="mb-4">Validation des Véhicules</h1>

        <form method="GET" action="{{ route('logistique.vehicules') }}" class="mb-4 mx-3">
            <div class="input-group">
                <input type="text" name="search" id="search" class="form-control"
                    placeholder="Rechercher un coursier par nom ou ID" value="{{ request('search') }}"
                    aria-label="Rechercher un coursier">
                <button type="submit" class="btn-entretien mx-2">Rechercher</button>
            </div>
        </form>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID Coursier</th>
                    <th>Nom Prénom Coursier</th>
                    <th>Véhicules en attente</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($coursiers as $coursier)
                    <tr>
                        <td>{{ $coursier->idcoursier }}</td>
                        <td>{{ $coursier->nomuser }} {{ $coursier->prenomuser }}</td>
                        <td>
                            @php
                                $pendingVehicles = $coursier->vehicules->whereIn('statusprocessuslogistique', [
                                    'En attente',
                                    'Modifications demandées',
                                ]);
                            @endphp
                            @if ($pendingVehicles->isNotEmpty())
                                <ul class="list-group">
                                    @foreach ($pendingVehicles as $vehicule)
                                        <li class="list-group-item">
                                            <div class="mb-2">
                                                <p>
                                                    {{ $vehicule->marque }}
                                                    {{ $vehicule->modele }} ({{ $vehicule->immatriculation }})
                                                </p>
                                                <p>
                                                    <strong>Statut:</strong>
                                                    <span>
                                                        {{ $vehicule->statusprocessuslogistique }}
                                                    </span>
                                                </p>
                                                <p>
                                                    <strong>Prestations:</strong>
                                                <ul>
                                                    @foreach ($vehicule->prestations as $prestation)
                                                        <li>{{ $prestation->libelleprestation }}</li>
                                                    @endforeach
                                                </ul>
                                                </p>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <span class="text-muted">Aucun véhicule en attente ou avec demande de modification</span>
                            @endif
                        </td>
                        <td>
                            @if ($pendingVehicles->isNotEmpty())
                                <div class="vehicle-actions">
                                    @foreach ($pendingVehicles as $vehicule)
                                        <li class="list-group-item">

                                            @if ($vehicule->statusprocessuslogistique === 'En attente')
                                                <div class="vehicle-item mb-3">
                                                    <div class="vehicle-info">
                                                        <strong>Marque & Modèle:</strong> {{ $vehicule->marque }}
                                                        {{ $vehicule->modele }}
                                                    </div>

                                                    <div class="action-buttons mt-2">
                                                        <form
                                                            action="{{ route('logistique.vehicules.valider', $vehicule->idvehicule) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit"
                                                                class="btn btn-success btn-sm">Valider</button>
                                                        </form>

                                                        <form
                                                            action="{{ route('logistique.vehicules.refuser', $vehicule->idvehicule) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit"
                                                                class="btn btn-danger btn-sm">Refuser</button>
                                                        </form>

                                                        <a href="{{ route('logistique.vehicules.modifierForm', ['id' => $vehicule->idvehicule]) }}"
                                                            class="btn btn-secondary btn-sm">
                                                            Demander aménagement(s)
                                                        </a>
                                                    </div>
                                                </div>
                                            @elseif ($vehicule->statusprocessuslogistique === 'Modifications demandées')
                                                <div>
                                                    <div class="vehicle-info">
                                                        <strong>Demande d'aménagement(s) en cours</strong>
                                                    </div>
                                                    <a href="{{ route('logistique.modifications') }}"
                                                        class="btn btn-secondary action-buttons mt-2">
                                                        Voir la demande
                                                    </a>
                                                </div>
                                            @endif

                                        </li>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-muted">Aucun véhicule en attente pour des actions</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Aucun coursier trouvé avec des véhicules en attente ou des
                            demandes de modification.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="d-flex justify-content-center mt-4">
            {{ $coursiers->onEachSide(1)->links('pagination::bootstrap-4') }}
        </div>
    </div>
@endsection
