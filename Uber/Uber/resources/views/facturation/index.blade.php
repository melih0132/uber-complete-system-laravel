@extends('layouts.app')

@section('title', 'Facturation')

@section('content')
    <div class="container py-5">
        <div class="card shadow-sm border-0">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Facturation</h2>
            </div>
            <div class="card-body">

                <div class="mb-2 border-0">
                    <div class="card-body">
                        <form method="POST" action="{{ route('facturation.filter') }}" class="row g-3">
                            @csrf

                            <div class="col-12 mb-2">
                                <label for="search-coursier" class="form-label text-secondary fw-bold">
                                    Rechercher un coursier
                                </label>
                                <div class="position-relative">
                                    <input type="text" id="search-coursier" class="form-control"
                                        placeholder="Nom, prénom ou ID du coursier">
                                    <input type="hidden" id="idcoursier" name="idcoursier" value="{{ $idcoursier }}">
                                    <ul id="suggestions" class="list-group position-absolute w-100" style="z-index: 1000;">
                                    </ul>
                                </div>
                                <small class="text-muted">Saisissez au moins 3 caractères pour afficher des
                                    suggestions.</small>
                            </div>

                            <div class="col-md-6">
                                <label for="start_date" class="form-label fw-bold text-secondary">Date de début</label>
                                <input type="date" id="start_date" name="start_date" class="form-control"
                                    value="{{ $start_date }}" required>
                            </div>

                            <div class="col-md-6">
                                <label for="end_date" class="form-label fw-bold text-secondary">Date de fin</label>
                                <input type="date" id="end_date" name="end_date" class="form-control"
                                    value="{{ $end_date }}" required>
                            </div>

                            <div class="col-12 text-end">
                                <button type="submit" class="btn-uber mt-4">
                                   Rechercher
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                @if (count($trips) > 0)
                    <h5 class="text-secondary"><i class="fas fa-list-ul me-2"></i> Résultats des courses</h5>
                    <div class="table-responsive">
                        <table class="table table-striped align-middle text-center table-bordered">
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
                                        <td>{{ $trip->statutcourse }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <h4>Total : {{ number_format($totalAmount, 2) }} €</h4>
                        <form method="POST" action="{{ route('facturation.generate') }}" target="_blank">
                            @csrf
                            <input type="hidden" name="idcoursier" value="{{ $idcoursier }}">
                            <input type="hidden" name="start_date" value="{{ $start_date }}">
                            <input type="hidden" name="end_date" value="{{ $end_date }}">
                            <button type="submit" class="btn-uber">
                                <i class="fas fa-file-pdf mx-2"></i> Télécharger la facture
                            </button>
                        </form>
                    </div>
                @else
                    <div class="alert mt-4 text-center">
                        <i class="fas fa-exclamation-circle me-2"></i> Aucune course trouvée pour cette période.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-coursier');
            const suggestionsList = document.getElementById('suggestions');
            const hiddenInput = document.getElementById('idcoursier');

            searchInput.addEventListener('input', function() {
                const query = searchInput.value.trim();
                if (query.length > 2) {
                    fetch(`{{ route('facturation.search-coursiers') }}?query=${query}`)
                        .then(response => response.json())
                        .then(data => {
                            suggestionsList.innerHTML = '';
                            if (data.length > 0) {
                                data.forEach(coursier => {
                                    const li = document.createElement('li');
                                    li.classList.add('list-group-item',
                                        'list-group-item-action');
                                    li.textContent =
                                        `${coursier.nomuser} ${coursier.prenomuser} (ID: ${coursier.idcoursier})`;
                                    li.dataset.idcoursier = coursier.idcoursier;
                                    suggestionsList.appendChild(li);

                                    li.addEventListener('click', function() {
                                        searchInput.value =
                                            `${coursier.nomuser} ${coursier.prenomuser}`;
                                        hiddenInput.value = coursier.idcoursier;
                                        suggestionsList.innerHTML = '';
                                    });
                                });
                            } else {
                                const li = document.createElement('li');
                                li.classList.add('list-group-item', 'text-muted');
                                li.textContent = 'Aucun coursier trouvé';
                                suggestionsList.appendChild(li);
                            }
                        })
                        .catch(error => console.error('Erreur:', error));
                } else {
                    suggestionsList.innerHTML = '';
                }
            });

            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !suggestionsList.contains(e.target)) {
                    suggestionsList.innerHTML = '';
                }
            });
        });
    </script>
@endsection
