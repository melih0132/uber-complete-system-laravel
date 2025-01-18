@extends('layouts.ubereats')

@section('title', 'Service Commande')

@section('content')
    <div class="container">
        <h1 class="mb-4 text-center">Service Commande - Liste des Commandes</h1>

        @if ($commandes->isEmpty())
            <p class="text-center">Aucune commande n'a été trouvée.</p>
        @else
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Prix</th>
                        <th>Statut</th>
                        <th>Créée le</th>
                        <th>Demande de Refus</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($commandes as $commande)
                        <tr>
                            <td>{{ $commande->idcommande }}</td>
                            <td>{{ number_format($commande->prixcommande, 2, ',', ' ') }} €</td>
                            <td>{{ $commande->statutcommande }}</td>
                            <td>{{ \Carbon\Carbon::parse($commande->heurecreation)->format('d/m/Y H:i') }}</td>
                            <td>
                                @if ($commande->refus_demandee)
                                    <span class="badge bg-warning">Refus demandé</span>
                                @else
                                    <span class="text-muted">Aucun</span>
                                @endif
                            </td>
                            <td>
                                <!-- Action pour enregistrer un refus -->
                                @if ($commande->refus_demandee && $commande->statutcommande !== 'Refusée' && $commande->statutcommande !== 'Remboursée')
                                    <form action="{{ route('commande.refuser', $commande->idcommande) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-warning btn-sm">Enregistrer le refus</button>
                                    </form>
                                @endif

                                <!-- Action pour rembourser -->
                                @if ($commande->statutcommande === 'Refusée' && !$commande->remboursement_effectue)
                                    <form action="{{ route('commande.rembourser', $commande->idcommande) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">Rembourser</button>
                                    </form>
                                @endif

                                <!-- Action pour mettre à jour le statut -->
                                <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#updateStatusModal{{ $commande->idcommande }}">Mettre à jour</button>
                            </td>
                        </tr>

                        <!-- Modal pour mettre à jour le statut -->
                        <div class="modal fade mt-5" id="updateStatusModal{{ $commande->idcommande }}" tabindex="-1"
                            aria-labelledby="updateStatusModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('commande.mettreAJourStatut', $commande->idcommande) }}"
                                        method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="updateStatusModalLabel">Mettre à jour le statut</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="statutcommande">Nouveau statut</label>
                                                <select name="statutcommande" id="statutcommande" class="form-control"
                                                    required>
                                                    <option value="En attente de paiement">En attente de paiement</option>
                                                    <option value="Paiement validé">Paiement validé</option>
                                                    <option value="En cours">En cours</option>
                                                    <option value="Livrée">Livrée</option>
                                                    <option value="Annulée">Annulée</option>
                                                    <option value="Refusée">Refusée</option>
                                                    <option value="Remboursée">Remboursée</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Annuler</button>
                                            <button type="submit" class="btn btn-primary">Mettre à jour</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $commandes->appends(request()->except('page'))->onEachSide(1)->links('pagination::bootstrap-4') }}
            </div>

        @endif
    </div>
@endsection
