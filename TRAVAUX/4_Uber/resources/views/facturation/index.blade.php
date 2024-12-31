@extends('layouts.app')

@section('title', 'Facturation')

@section('content')
    <div class="container py-5">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Facturation</h2>
                <i class="fas fa-file-invoice-dollar fa-2x"></i>
            </div>
            <div class="card-body">
                {{-- Affichage des erreurs --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Formulaire de filtrage --}}
                <h5 class="mb-4 text-secondary"><i class="fas fa-filter me-2"></i> Filtrer les courses</h5>
                <form method="POST" action="{{ route('facturation.filter') }}" class="row g-3 mb-4">
                    @csrf
                    <div class="col-md-4">
                        <label for="idcoursier" class="form-label fw-bold">Coursier</label>
                        <select id="idcoursier" name="idcoursier" class="form-select shadow-sm" required>
                            <option value="" selected>Choisissez un coursier</option>
                            @foreach ($coursiers as $coursier)
                                <option value="{{ $coursier->idcoursier }}"
                                    {{ old('idcoursier', $idcoursier) == $coursier->idcoursier ? 'selected' : '' }}>
                                    {{ $coursier->nomuser }} {{ $coursier->prenomuser }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="start_date" class="form-label fw-bold">Date de début</label>
                        <input type="date" id="start_date" name="start_date" class="form-control shadow-sm"
                            value="{{ old('start_date', $start_date) }}" required>
                    </div>
                    <div class="col-md-3">
                        <label for="end_date" class="form-label fw-bold">Date de fin</label>
                        <input type="date" id="end_date" name="end_date" class="form-control shadow-sm"
                            value="{{ old('end_date', $end_date) }}" required>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-success w-100 shadow">
                            <i class="fas fa-search me-2"></i> Rechercher
                        </button>
                    </div>
                </form>

                {{-- Résultats du filtrage --}}
                @if (count($trips) > 0)
                    <div class="mt-4">
                        <h5 class="mb-3 text-secondary"><i class="fas fa-list-ul me-2"></i> Résultats</h5>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped align-middle shadow-sm">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID Course</th>
                                        <th>Date</th>
                                        <th>Prix (€)</th>
                                        <th>Pourboire (€)</th>
                                        <th>Distance (km)</th>
                                        <th>Temps (min)</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($trips as $trip)
                                        <tr>
                                            <td>{{ $trip->idcourse }}</td>
                                            <td>{{ \Carbon\Carbon::parse($trip->datecourse)->format('d/m/Y') }}</td>
                                            <td>{{ number_format($trip->prixcourse, 2) }}</td>
                                            <td>{{ $trip->pourboire ? number_format($trip->pourboire, 2) : '0.00' }}</td>
                                            <td>{{ $trip->distance ? number_format($trip->distance, 2) : '-' }}</td>
                                            <td>{{ $trip->temps ? $trip->temps : '-' }}</td>
                                            <td>
                                                @if ($trip->statutcourse == 'Terminée')
                                                    <span>Terminée</span>
                                                @elseif ($trip->statutcourse == 'Annulée')
                                                    <span>Annulée</span>
                                                @else
                                                    <span>{{ $trip->statutcourse }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-end align-items-center mt-3">
                            <h4 class="ms-4">Total : <span>{{ number_format($totalAmount, 2) }}€</span></h4>
                        </div>
                        <div class="d-flex justify-content-end align-items-center mt-3">
                            <form method="POST" action="{{ route('facturation.generate') }}">
                                @csrf
                                <input type="hidden" name="idcoursier" value="{{ $idcoursier }}">
                                <input type="hidden" name="start_date" value="{{ $start_date }}">
                                <input type="hidden" name="end_date" value="{{ $end_date }}">
                                <button type="submit" class="btn btn-primary shadow">
                                    <i class="fas fa-file-pdf me-2"></i> Télécharger la Facture
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning mt-5 text-center">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Aucune course trouvée pour cette période.
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
