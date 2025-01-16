@extends('layouts.app')

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
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="coursiers-table-body">
                @foreach ($coursiers as $coursier)
                    <tr>
                        <td>{{ $coursier->idcoursier }}</td>
                        <td>{{ $coursier->nomuser }}</td>
                        <td>{{ $coursier->prenomuser }}</td>
                        <td>
                            <form action="{{ route('admin.validation.relancer', $coursier->idcoursier) }}" method="POST"
                                class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-sm">Relancer</button>
                            </form>
                            <form action="{{ route('admin.validation.supprimer', $coursier->idcoursier) }}" method="POST"
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
                                <tr>
                                    <td>${coursier.idcoursier}</td>
                                    <td>${coursier.nomuser}</td>
                                    <td>${coursier.prenomuser}</td>
                                    <td>
                                        <form action="/admin/validation/relancer/${coursier.idcoursier}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-warning btn-sm">Relancer</button>
                                        </form>
                                        <form action="/admin/validation/supprimer/${coursier.idcoursier}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                                        </form>
                                    </td>
                                </tr>
                            `);
                        });
                    },
                });
            });
        });
    </script>
@endsection
