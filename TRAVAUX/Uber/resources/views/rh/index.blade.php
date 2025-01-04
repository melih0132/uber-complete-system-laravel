@extends('layouts.app')

@section('title', 'Uber RH')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/entretien.blade.css') }}">
@endsection

@section('content')
    <div class="container mt-5">

        <div class="mb-4">
            <form action="{{ route('entretiens.rechercher') }}" method="GET" class="d-flex align-items-center">
                <input type="text" name="search" class="form-control me-2"
                    placeholder="Rechercher un coursier (Nom, Prénom, ID)" value="{{ request('search') }}">
                <button type="submit" class="btn-entretien">Rechercher</button>
            </form>
        </div>

        <h1 class="mb-4">Liste des entretiens</h1>

        {{-- Table des entretiens --}}
        <div class="table-responsive">
            <table class="table-striped align-middle">
                <thead class="table-uber">
                    <tr>
                        <th>ID</th>
                        <th class="text-center">Coursier</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Statut</th>
                        <th class="text-center">Entretien</th>
                        <th class="text-center">Validation</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($entretiens as $entretien)
                        <tr class="table-bordered">
                            <td class="text-center">{{ $entretien->identretien }}</td>
                            <td class="text-center">
                                {{ $entretien->coursier->nomuser ?? 'Inconnu' }}
                                {{ $entretien->coursier->prenomuser ?? '' }}
                            </td>
                            <td class="text-center">
                                {{ $entretien->dateentretien ? $entretien->dateentretien->format('d/m/Y H:i') : 'Non défini' }}
                            </td>
                            <td class="text-center">
                                <span>
                                    {{ $entretien->status }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if ($entretien->status !== 'Terminée')
                                    @if ($entretien->status === 'En attente')
                                        <div class="d-flex justify-content-center">
                                            <a href="{{ route('entretiens.planifierForm', $entretien->identretien) }}"
                                                class="btn-entretien text-decoration-none">Planifier</a>
                                        </div>
                                    @endif


                                    @if ($entretien->status === 'Planifié')
                                        <form class="d-flex justify-content-center"
                                            action="{{ route('entretiens.resultat', ['id' => $entretien->identretien]) }}"
                                            method="POST" style="display:inline;">
                                            @csrf
                                            <div class="d-inline-flex justify-content-center align-items-center">
                                                <select name="status" class="form-select form-select-sm me-2" required>
                                                    <option value="Terminée">Terminée</option>
                                                    <option value="Annulée">Annulée</option>
                                                </select>
                                                <button type="submit" class="btn-entretien">Valider</button>
                                            </div>
                                        </form>
                                    @endif
                                @else
                                    <span class="text-muted">Non applicable</span>
                                @endif
                            </td>
                            <td class="text-center">
                                {{-- Validation ou Refus du coursier --}}
                                @if ($entretien->status === 'Terminée')
                                    <div class="d-flex justify-content-center">
                                        <form
                                            action="{{ route('entretiens.validerCoursier', $entretien->coursier->idcoursier ?? null) }}"
                                            method="POST" class="d-inline mx-1">
                                            @csrf
                                            <button type="submit" class="btn-valider">Valider le coursier</button>
                                        </form>
                                        <form
                                            action="{{ route('entretiens.refuserCoursier', $entretien->coursier->idcoursier ?? null) }}"
                                            method="POST" class="d-inline mx-1">
                                            @csrf
                                            <button type="submit" class="btn-refuser">Refuser le coursier</button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-muted">Non applicable</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Aucun entretien trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
