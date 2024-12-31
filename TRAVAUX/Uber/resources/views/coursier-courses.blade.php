@extends('layouts.app')

@section('title', 'Facturation')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/coursier-courses.blade.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection

@section('content')
<div class="container py-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0"><i class="fas fa-file-invoice-dollar me-2"></i>Facturation</h2>
        </div>
        <div class="card-body">
            <h3 class="mb-4"><i class="fas fa-filter me-2"></i>Filtrer les Courses</h3>
            <form method="POST" action="{{ route('coursier.courses.filter') }}" class="row g-3">
                @csrf
                <div class="col-md-4">
                    <label for="idcoursier" class="form-label">Sélectionnez Courrier</label>
                    <select class="form-select" id="idcoursier" name="idcoursier" required>
                        <option value="" disabled selected>Choisissez un coursier</option>
                        @foreach($coursiers as $coursier)
                            <option value="{{ $coursier->idcoursier }}"
                                @if(old('idcoursier', request()->idcoursier) == $coursier->idcoursier) selected @endif>
                                {{ $coursier->nomuser }} {{ $coursier->prenomuser }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="start_date" class="form-label">Date de début</label>
                    <input type="date" class="form-control" id="start_date" name="start_date"
                           value="{{ old('start_date', request()->start_date) }}" required>
                </div>

                <div class="col-md-3">
                    <label for="end_date" class="form-label">Date de fin</label>
                    <input type="date" class="form-control" id="end_date" name="end_date"
                           value="{{ old('end_date', request()->end_date) }}" required>
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-filter me-2"></i>Filtrer
                    </button>
                </div>
            </form>



            @if(count($trips) > 0)
                <div class="mt-5">
                    <h3 class="mb-3">
                        <i class="fas fa-list-alt me-2"></i>
                        Liste des Courses pour {{ $selectedCoursier->nomuser }} {{ $selectedCoursier->prenomuser }}
                    </h3>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Date</th>
                                    <th>Prix (€)</th>
                                    <th>Pourboire (€)</th>
                                    <th>Distance (km)</th>
                                    <th>Temps (min)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($trips as $trip)
                                <tr>
                                    <td>{{ $trip->idcourse }}</td>
                                    <td>{{ \Carbon\Carbon::parse($trip->datecourse)->format('d/m/Y') }}</td>
                                    <td>{{ number_format($trip->prixcourse, 2) }}</td>
                                    <td>{{ number_format($trip->pourboire, 2) }}</td>
                                    <td>{{ number_format($trip->distance, 2) }}</td>
                                    <td>{{ $trip->temps }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end align-items-center mt-3">
                        <h4 class="me-3">Montant total:</h4>
                        <span class="badge bg-success fs-5">€{{ number_format($totalAmount, 2) }}</span>
                    </div>
                </div>
            @else
                <div class="alert alert-warning mt-5" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>Aucun voyage trouvé pour le coursier et la période sélectionnés.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
