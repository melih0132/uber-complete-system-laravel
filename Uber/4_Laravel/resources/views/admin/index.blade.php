@extends('layouts.app')

@section('title', 'Gestion des Coursiers')

@section('content')
    <div class="container mt-5">
        <h1 class="mb-4">Gestion des Coursiers</h1>

        <!-- Formulaire de recherche -->
        <form id="search-form" class="mb-4">
            <div class="input-group">
                <input type="text" class="form-control" id="search-query" placeholder="Rechercher un coursier...">
                <button type="submit" class="btn btn-primary">Rechercher</button>
            </div>
        </form>

        <!-- Tableau des coursiers -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>IBAN</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="coursiers-table-body">
                @foreach ($coursiers as $coursier)
                    <tr id="row-{{ $coursier->idcoursier }}">
                        <td>{{ $coursier->idcoursier }}</td>
                        <td>{{ $coursier->nomuser }}</td>
                        <td>{{ $coursier->prenomuser }}</td>
                        <td>
                            @if ($coursier->iban)
                                <span class="text-success"><i class="fas fa-check-circle"></i> Renseigné</span>
                            @else
                                <span class="text-danger"><i class="fas fa-exclamation-circle"></i> Non renseigné</span>
                            @endif
                        </td>
                        <td>
                            <!-- Demander IBAN si non renseigné -->
                            @if (empty($coursier->iban))
                                <form action="{{ route('admin.demander-iban', $coursier->idcoursier) }}" method="POST"
                                    class="d-inline demande-iban-form">
                                    @csrf
                                    <button type="submit" class="btn btn-info btn-sm">
                                        <i class="fas fa-university"></i> Demander IBAN
                                    </button>
                                </form>
                            @endif

                            <!-- Supprimer Coursier -->
                            <form action="{{ route('admin.validation.supprimer', $coursier->idcoursier) }}" method="POST"
                                class="d-inline delete-coursier-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash-alt"></i> Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        @if ($coursiers instanceof \Illuminate\Pagination\LengthAwarePaginator && $coursiers->hasPages())
            <div class="mt-4">
                {{ $coursiers->links() }}
            </div>
        @endif
    </div>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Recherche AJAX des coursiers
            $('#search-form').on('submit', function(e) {
                e.preventDefault();
                const query = $('#search-query').val();

                $.ajax({
                    url: '{{ route('admin.search-coursiers') }}',
                    method: 'GET',
                    data: {
                        query
                    },
                    success: function(data) {
                        const tbody = $('#coursiers-table-body');
                        tbody.empty();

                        data.forEach(coursier => {
                            tbody.append(`
                                <tr id="row-${coursier.idcoursier}">
                                    <td>${coursier.idcoursier}</td>
                                    <td>${coursier.nomuser}</td>
                                    <td>${coursier.prenomuser}</td>
                                    <td>
                                        ${coursier.iban ?
                                            `<span class="text-success"><i class="fas fa-check-circle"></i> Renseigné</span>` :
                                            `<span class="text-danger"><i class="fas fa-exclamation-circle"></i> Non renseigné</span>`
                                        }
                                    </td>
                                    <td>
                                        ${coursier.iban ? '' : `
                                                <form action="/admin/demander-iban/${coursier.idcoursier}" method="POST" class="d-inline demande-iban-form">
                                                    @csrf
                                                    <button type="submit" class="btn btn-info btn-sm">
                                                        <i class="fas fa-university"></i> Demander IBAN
                                                    </button>
                                                </form>
                                            `}
                                        <form action="/admin/validation/supprimer/${coursier.idcoursier}" method="POST" class="d-inline delete-coursier-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash-alt"></i> Supprimer
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            `);
                        });
                    },
                });
            });

            // Gestion AJAX pour la demande d'IBAN
            $(document).on('submit', '.demande-iban-form', function(e) {
                e.preventDefault();
                const form = $(this);
                const actionUrl = form.attr('action');

                $.ajax({
                    url: actionUrl,
                    method: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        alert('Demande d’IBAN envoyée avec succès.');
                        form.closest('td').html(
                            '<span class="text-warning"><i class="fas fa-clock"></i> Demande envoyée</span>'
                            );
                    },
                    error: function() {
                        alert('Erreur lors de l’envoi de la demande.');
                    }
                });
            });

            // Gestion AJAX pour la suppression d'un coursier
            $(document).on('submit', '.delete-coursier-form', function(e) {
                e.preventDefault();
                const form = $(this);
                const actionUrl = form.attr('action');
                const rowId = form.closest('tr').attr('id');

                if (confirm("Voulez-vous vraiment supprimer ce coursier ?")) {
                    $.ajax({
                        url: actionUrl,
                        method: 'POST',
                        data: form.serialize(),
                        success: function(response) {
                            alert('Coursier supprimé avec succès.');
                            $('#' + rowId).remove(); // Supprime la ligne du tableau
                        },
                        error: function() {
                            alert('Erreur lors de la suppression.');
                        }
                    });
                }
            });
        });
    </script>
@endsection
