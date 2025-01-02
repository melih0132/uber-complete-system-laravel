@extends('layouts.app')

@section('content')
    <h1>Commandes prévues pour la prochaine heure</h1>

    @if (session('info'))
        <div class="alert alert-info">{{ session('info') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>ID Commande</th>
                <th>Prix (€)</th>
                <th>Nom du Client</th>
                <th>Téléphone</th>
                <th>Heure prévue</th>
                <th>Assigner un Coursier</th>
            </tr>
        </thead>
        <tbody>
            @forelse($commandes as $commande)
                <tr>
                    <td>{{ $commande['id_commande'] }}</td>
                    <td>{{ $commande['prix'] }}</td>
                    <td>{{ $commande['nom_client'] }}</td>
                    <td>{{ $commande['telephone'] }}</td>
                    <td>{{ $commande['heure_prev'] }}</td>
                    <td>
                        <form action="{{ route('assignerLivreur', $commande['id_commande']) }}" method="POST">
                            @csrf
                            <div class="position-relative">
                                <input type="text" class="form-control search-coursier"
                                    data-command-id="{{ $commande['id_commande'] }}" placeholder="Rechercher un coursier">
                                <input type="hidden" name="idcoursier" id="idcoursier-{{ $commande['id_commande'] }}">
                                <ul id="suggestions-{{ $commande['id_commande'] }}"
                                    class="list-group position-absolute w-100" style="z-index: 1000;"></ul>
                            </div>
                            <button type="submit" class="btn btn-primary mt-2">Assigner</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Aucune commande disponible.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInputs = document.querySelectorAll('.search-coursier');

        searchInputs.forEach((input) => {
            const commandId = input.dataset.commandId;
            const suggestionsList = document.getElementById(`suggestions-${commandId}`);
            const hiddenInput = document.getElementById(`idcoursier-${commandId}`);

            input.addEventListener('input', function() {
                const query = input.value.trim();
                if (query.length > 2) {
                    fetch(`{{ route('manager.search-coursiers') }}?query=${query}`)
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
                                        input.value =
                                            `${coursier.nomuser} ${coursier.prenomuser}`;
                                        hiddenInput.value = coursier
                                            .idcoursier;
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
                if (!input.contains(e.target) && !suggestionsList.contains(e.target)) {
                    suggestionsList.innerHTML = '';
                }
            });
        });
    });
</script>
