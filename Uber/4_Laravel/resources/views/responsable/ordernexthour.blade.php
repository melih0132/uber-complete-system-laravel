@extends('layouts.app')

@section('title', 'Commandes urgentes')

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
                <th>Assigner un livreur</th>
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
                        @if (isset($commande['estlivraison']) && $commande['estlivraison'])
                            <form action="{{ route('responsable.assignerlivreur', $commande['id_commande']) }}"
                                method="POST">
                                @csrf
                                <div class="position-relative">
                                    <input type="text" class="form-control search-livreur"
                                        data-command-id="{{ $commande['id_commande'] }}" placeholder="Rechercher un livreur">
                                    <input type="hidden" name="idlivreur" id="idlivreur-{{ $commande['id_commande'] }}">
                                    <ul id="suggestions-{{ $commande['id_commande'] }}"
                                        class="list-group position-absolute w-100 suggestions-list"
                                        style="z-index: 1000; display: none;"></ul>
                                </div>
                                <button type="submit" class="btn btn-primary mt-2">Assigner</button>
                            </form>
                        @else
                            <span class="text-muted">Non livrable</span>
                        @endif
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

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInputs = document.querySelectorAll('.search-livreur');

            searchInputs.forEach((input) => {
                const commandId = input.dataset.commandId;
                const suggestionsList = document.getElementById(`suggestions-${commandId}`);
                const hiddenInput = document.getElementById(`idlivreur-${commandId}`);

                input.addEventListener('input', function() {
                    const query = input.value.trim();

                    if (query.length > 2) {
                        fetch(`{{ route('responsable.search-livreur') }}?query=${query}`)
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error(
                                        'Erreur lors de la récupération des données');
                                }
                                return response.json();
                            })
                            .then(data => {
                                suggestionsList.innerHTML = '';
                                suggestionsList.style.display = 'block';

                                if (data.length > 0) {
                                    data.forEach(livreur => {
                                        const li = document.createElement('li');
                                        li.classList.add('list-group-item',
                                            'list-group-item-action');
                                        li.textContent =
                                            `${livreur.nomuser} ${livreur.prenomuser} (ID: ${livreur.idlivreur})`;
                                        li.dataset.idlivreur = livreur.idlivreur;
                                        suggestionsList.appendChild(li);

                                        li.addEventListener('click', function() {
                                            input.value =
                                                `${livreur.nomuser} ${livreur.prenomuser}`;
                                            hiddenInput.value = livreur
                                                .idlivreur;
                                            suggestionsList.innerHTML = '';
                                            suggestionsList.style.display =
                                                'none';
                                        });
                                    });
                                } else {
                                    const li = document.createElement('li');
                                    li.classList.add('list-group-item', 'text-muted');
                                    li.textContent = 'Aucun livreur trouvé';
                                    suggestionsList.appendChild(li);
                                }
                            })
                            .catch(error => {
                                console.error('Erreur:', error);
                                suggestionsList.innerHTML =
                                    '<li class="list-group-item text-danger">Erreur lors de la recherche</li>';
                                suggestionsList.style.display = 'block';
                            });
                    } else {
                        suggestionsList.innerHTML = '';
                        suggestionsList.style.display = 'none';
                    }
                });

                document.addEventListener('click', function(e) {
                    if (!input.contains(e.target) && !suggestionsList.contains(e.target)) {
                        suggestionsList.innerHTML = '';
                        suggestionsList.style.display = 'none';
                    }
                });
            });
        });
    </script>
@endsection
