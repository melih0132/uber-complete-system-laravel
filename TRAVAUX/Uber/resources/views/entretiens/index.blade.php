@extends('layouts.app')

@section('title', 'UBER RH')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/entretien.blade.css') }}">
@endsection

@section('content')
    <div class="container">
        <h1 class="mb-4">Liste des entretiens</h1>

        {{-- Table des entretiens --}}
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Coursier</th>
                        <th>Date</th>
                        <th>Statut</th>
                        <th>Actions</th>
                        <th>Validation</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($entretiens as $entretien)
                        <tr>
                            {{-- Détails de l'entretien --}}
                            <td>{{ $entretien->identretien }}</td>
                            <td>
                                {{ $entretien->coursier->nomuser ?? 'Inconnu' }}
                                {{ $entretien->coursier->prenomuser ?? '' }}
                            </td>
                            <td>
                                {{ $entretien->dateentretien ? $entretien->dateentretien->format('d/m/Y H:i') : 'Non défini' }}
                            </td>
                            <td>
                                <span>
                                    {{ $entretien->status }}
                                </span>
                            </td>
                            <td>
                                @if ($entretien->status !== 'Terminée')
                                    {{-- Action: Planifier --}}
                                    @if ($entretien->status === 'En attente')
                                        <a href="{{ route('entretiens.planifierForm', $entretien->identretien) }}"
                                            class="btn btn-primary btn-sm">Planifier</a>
                                    @endif

                                    {{-- Action: Enregistrer Résultat --}}
                                    @if ($entretien->status === 'Planifié')
                                        <form action="{{ route('entretiens.resultat', ['id' => $entretien->identretien]) }}"
                                            method="POST" style="display:inline;">
                                            @csrf
                                            <div class="d-inline-flex align-items-center">
                                                <select name="status" class="form-select form-select-sm me-2" required
                                                    style="width:auto;">
                                                    <option value="Terminée">Terminée</option>
                                                    <option value="Annulée">Annulée</option>
                                                </select>
                                                <button type="submit" class="btn btn-success btn-sm">Valider</button>
                                            </div>
                                        </form>
                                    @endif
                                @else
                                    <span class="text-muted">Non applicable</span>
                                @endif
                            </td>
                            <td>
                                {{-- Validation ou Refus du coursier --}}
                                @if ($entretien->status === 'Terminée')
                                    <form
                                        action="{{ route('entretiens.validerCoursier', $entretien->coursier->idcoursier ?? null) }}"
                                        method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">Valider le coursier</button>
                                    </form>
                                    <form
                                        action="{{ route('entretiens.refuserCoursier', $entretien->coursier->idcoursier ?? null) }}"
                                        method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">Refuser le coursier</button>
                                    </form>
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
