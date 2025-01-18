@extends($layout)

@section('title', 'Mon compte')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/myaccount.blade.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endsection

@section('content')
    <div class="container my-5">

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="account mt-5">
            <h1 class="mb-4 text-center">Mon compte</h1>
            <div class="row">
                <!-- Sidebar -->
                <div class="col-md-3">
                    <ul id="sidebar-menu" class="list-group shadow-sm">
                        <li class="list-group-item active" data-target="content-informations">
                            <i class="fas fa-user me-2"></i>Informations compte
                        </li>

                        @if ($role === 'client' && (!$isUberEatsUser ?? true))
                            <li class="list-group-item" data-target="content-lieux-favoris">
                                <i class="fas fa-star me-2"></i> Lieux favoris
                            </li>
                            <li class="list-group-item" data-target="content-historique-courses">
                                <i class="fas fa-history me-2"></i> Planning des courses
                            </li>
                            <li class="list-group-item">
                                <a href="{{ url('/carte-bancaire') }}"
                                    class="text-decoration-none d-flex align-items-center">
                                    <i class="fas fa-credit-card me-2" aria-hidden="true"></i> Carte Bancaire
                                </a>
                            </li>
                        @endif

                        @if ($role === 'coursier')
                            <li class="list-group-item" data-target="content-vehicules">
                                <i class="fas fa-car me-2"></i>Mes véhicules
                            </li>
                            <li class="list-group-item" data-target="content-entretien">
                                <i class="fas fa-user-cog me-2"></i> Entretien
                            </li>
                        @endif

                        @if ($role === 'restaurateur')
                            <li class="list-group-item" data-target="content-etablissements">
                                <i class="fas fa-store me-2"></i> Mes établissements
                            </li>
                        @endif

                        <li class="list-group-item" data-target="content-securite">
                            <i class="fas fa-shield-alt me-2"></i> Sécurité

                        </li>

                        <li class="list-group-item" data-target="content-confidentialite">
                            <i class="fas fa-user-shield me-2"></i> Confidentialité et données
                        </li>
                    </ul>
                </div>

                <!-- Main Content -->
                <div class="col-md-9">
                    <div class="p-4">
                        <!-- Content sections -->
                        <div id="content-informations" class="content-section active">
                            @if ($role === 'client')
                                <div class="mb-4">
                                    <img src="{{ $user->photoprofile ? asset('storage/' . $user->photoprofile) : 'https://institutcommotions.com/wp-content/uploads/2018/05/blank-profile-picture-973460_960_720-1.png' }}"
                                        alt="Photo de profil" class="pdp_picture" id="profileImage">
                                    <form action="{{ route('update.profile.image') }}" method="POST"
                                        enctype="multipart/form-data" class="mt-3">
                                        @csrf
                                        <label for="profile_image" class="link-photo">Modifier la photo</label>
                                        <input type="file" id="profile_image" name="profile_image" style="display: none;"
                                            accept="image/*" onchange="this.form.submit()">
                                    </form>
                                    @error('profile_image')
                                        <div class="alert alert-danger mt-2">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            @endif

                            <!-- Account Information -->
                            <div class="row">
                                @if (in_array($role, ['client', 'coursier', 'livreur', 'responsable', 'restaurateur']))
                                    <div class="col-12">
                                        <p><strong>Prénom :</strong> {{ $user->prenomuser ?? 'Non renseigné' }}</p>
                                    </div>
                                    <div class="col-12">
                                        <p><strong>Nom :</strong> {{ $user->nomuser ?? 'Non renseigné' }}</p>
                                    </div>
                                    <div class="col-12">
                                        <p><strong>Numéro de téléphone :</strong> {{ $user->telephone ?? 'Non renseigné' }}
                                        </p>
                                    </div>
                                    <div class="col-12">
                                        <p><strong>Adresse mail :</strong> {{ $user->emailuser ?? 'Non renseigné' }}</p>
                                    </div>
                                @else
                                    <div class="col-12">
                                        <p><strong>Adresse mail :</strong> {{ $user['email'] ?? 'Non renseigné' }}</p>
                                    </div>
                                @endif
                                <div class="col-12">
                                    <p><strong>Rôle :</strong>
                                        {{ $role === 'responsable' ? 'Responsable d\'Enseigne' : ucfirst($role) }}
                                    </p>
                                </div>
                            </div>

                            @if ($role === 'coursier')
                                {{-- TEMPORAIRE --}}
                                @if (!$iban)
                                    <a href="{{ route('coursier.iban') }}" class="btn-compte text-decoration-none">Ajouter
                                        mon IBAN
                                    </a>
                                @endif

                                <div class="alert {{ $canDrive ? 'alert-success' : 'alert-danger' }} mt-4">
                                    <i class="fas {{ $canDrive ? 'fa-check-circle' : 'fa-times-circle' }} me-2"></i>
                                    {{ $canDrive ? 'Vous pouvez conduire.' : 'Vous ne pouvez pas conduire.' }}
                                </div>
                            @endif
                        </div>

                        <!-- Client-specific sections -->
                        @if ($role === 'client' && (!$isUberEatsUser ?? true))
                            <!-- Favorite Places -->
                            <div id="content-lieux-favoris" class="content-section">
                                <h2 class="h4 mb-4">Lieux favoris</h2>
                                <form action="{{ route('account.favorites.add') }}" method="POST" class="mb-4">
                                    @csrf
                                    <div class="row g-3 align-items-center">
                                        <div class="col-md-4">
                                            <input type="text" name="nomlieu" class="form-control"
                                                placeholder="Nom du lieu (ex : Maison)" required>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" name="libelleadresse" class="form-control"
                                                placeholder="Adresse complète" required>
                                        </div>
                                        <div class="col-md-3">
                                            <select name="idville" class="form-control" required>
                                                <option value="" disabled selected>Ville</option>
                                                @foreach ($villes as $ville)
                                                    <option value="{{ $ville->idville }}">{{ $ville->nomville }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-1">
                                            <button type="submit" class="btn-compte p-2">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>

                                @if ($favorites->isEmpty())
                                    <div class="alert alert-info">Aucun lieu favori enregistré pour le moment.</div>
                                @else
                                    <div class="list-group">
                                        @foreach ($favorites as $favorite)
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <span class="fw-bold pr-5">{{ $favorite->nomlieu }}</span>
                                                    <span class="text-muted">
                                                        @if ($favorite->adresse)
                                                            {{ $favorite->adresse->libelleadresse }}
                                                            @if ($favorite->adresse->ville)
                                                                , {{ $favorite->adresse->ville->nomville }}
                                                            @else
                                                                , <span class="text-danger">Ville inconnue</span>
                                                            @endif
                                                        @else
                                                            <span class="text-danger">Adresse inconnue</span>
                                                        @endif
                                                    </span>
                                                </div>
                                                <form
                                                    action="{{ route('account.favorites.delete', $favorite->idlieufavori) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <!-- Ride History -->
                            <div id="content-historique-courses" class="content-section">
                                <h2 class="h4 mb-4">Historique des courses</h2>
                                @if ($courses->isEmpty())
                                    <div class="alert alert-info">Votre historique de courses est vide pour le moment.
                                    </div>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover">
                                            <thead class="table-uber">
                                                <tr>
                                                    <th scope="col">Date & Heure</th>
                                                    <th scope="col">Prix</th>
                                                    <th scope="col">Statut</th>
                                                    <th scope="col">Note & Pourboire</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($courses as $course)
                                                    <tr>
                                                        <td>
                                                            {{ \Carbon\Carbon::parse($course->datecourse)->format('d/m/Y') }}
                                                            à
                                                            {{ \Carbon\Carbon::parse($course->heurecourse)->format('H:i') }}
                                                        </td>
                                                        <td>{{ number_format($course->prixcourse, 2) }} €</td>
                                                        <td>
                                                            <span
                                                                class="badge bg-{{ $course->statutcourse === 'terminée' ? 'success' : ($course->statutcourse === 'annulée' ? 'danger' : 'primary') }}">
                                                                {{ ucfirst($course->statutcourse) }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            @if ($course->notecourse != null)
                                                                <strong>Note :</strong>
                                                                {{ number_format($course->notecourse, 1) }}/5<br>
                                                            @else
                                                                <span class="text-muted">Non notée</span><br>
                                                            @endif
                                                            @if ($course->pourboire != 0)
                                                                <strong>Pourboire :</strong>
                                                                {{ $course->pourboire }}
                                                            @else
                                                                <span class="text-muted">Aucun Pourboire</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Courier-specific sections -->
                        @if ($role === 'coursier')
                            <!-- Vehicles -->
                            <div id="content-vehicules" class="content-section">
                                <h2 class="h4 mb-4">Mes véhicules</h2>
                                @if ($vehicules->isEmpty())
                                    <div class="alert alert-info">Aucun véhicule enregistré.</div>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Véhicule</th>
                                                    <th>Immatriculation</th>
                                                    <th>Statut</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($vehicules as $vehicule)
                                                    <tr>
                                                        <td>{{ $vehicule->marque }} {{ $vehicule->modele }} -
                                                            {{ $vehicule->couleur }}</td>
                                                        <td>{{ $vehicule->immatriculation }}</td>
                                                        <td>
                                                            <span
                                                                class="badge bg-{{ $vehicule->statusprocessuslogistique === 'Validé' ? 'success' : 'danger' }}">
                                                                {{ ucfirst($vehicule->statusprocessuslogistique) }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>

                            <!-- Maintenance -->
                            <div id="content-entretien" class="content-section">
                                <h2 class="h4 mb-4">Entretien</h2>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Date :</strong>
                                                    {{ $entretien->dateentretien ? $entretien->dateentretien->format('d/m/Y H:i') : 'N/A' }}
                                                </p>
                                                <p><strong>Statut :</strong> {{ ucfirst($entretien->status) }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Résultat :</strong>
                                                    {{ $entretien->resultat ?? 'Non défini' }}
                                                </p>
                                                @if ($entretien->resultat === 'Retenu' && $entretien->rdvlogistiquedate && $entretien->rdvlogistiquelieu)
                                                    <p><strong>RDV Logistique :</strong></p>
                                                    <ul class="list-unstyled ms-3">
                                                        <li>Date :
                                                            {{ $entretien->rdvlogistiquedate->format('d/m/Y H:i') }}
                                                        </li>
                                                        <li>Lieu : {{ $entretien->rdvlogistiquelieu }}</li>
                                                    </ul>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Business owner sections -->
                        @if ($role === 'restaurateur')
                            <div id="content-etablissements" class="content-section">
                                <h2 class="h4 mb-4">Mes établissements</h2>
                                @if ($etablissements->isEmpty())
                                    <div class="alert alert-info">Aucun établissement trouvé.</div>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Nom de l'établissement</th>
                                                    <th>Description</th>
                                                    <th>Catégories</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($etablissements as $etablissement)
                                                    <tr>
                                                        <td>{{ $etablissement->nometablissement }}</td>
                                                        <td>{{ $etablissement->description }}</td>
                                                        <td>
                                                            @forelse ($etablissement->categories as $categorie)
                                                                <span class="badge bg-primary me-1">
                                                                    {{ ucfirst($categorie->libellecategorieprestation) }}
                                                                </span>
                                                            @empty
                                                                <span class="text-muted">Aucune catégorie
                                                                    associée</span>
                                                            @endforelse
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Security section -->
                        <div id="content-securite" class="content-section">
                            <h2 class="h4 mb-4">Sécurité</h2>
                            <div>
                                <p>Configurer vos paramètres de sécurité ici.</p>

                                @if ($role === 'client')
                                    <!-- MFA Activation Section -->
                                    @if ($user->mfa_activee)
                                        <p>L’authentification multi-facteur est déjà activée sur votre compte.</p>
                                    @else
                                        <div class="mfa-section">
                                            <h3 class="h5 mb-3">Authentification Multi-Facteur (AMF)</h3>
                                            <p>
                                                Augmentez la sécurité de votre compte en activant l’authentification
                                                multi-facteur.
                                                Cette fonctionnalité ajoutera une étape supplémentaire à la connexion.
                                            </p>
                                            <p>
                                                Votre numéro de téléphone enregistré :
                                                <strong>{{ $user->telephone }}</strong>
                                            </p>
                                            <form action="{{ route('activateMFA') }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn-compte mt-3">Activer l’AMF</button>
                                            </form>
                                        </div>
                                    @endif
                                @endif
                                <!-- Bouton pour changer le mot de passe -->
                                <div class="mt-4">
                                    <a href="{{ route('password.reset') }}" class="btn-compte text-decoration-none">
                                        <i class="fas fa-key mx-2"></i> Changer le mot de passe
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Privacy section -->
                        <div id="content-confidentialite" class="content-section">
                            <h2 class="h4 mb-4">Confidentialité et données</h2>

                            @if (in_array($role, ['client', 'coursier', 'livreur', 'responsable', 'restaurateur']))

                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td><i class="fas fa-user"></i> <strong>Nom Prénom</strong></td>
                                            <td>{{ $user->nomuser }} {{ $user->prenomuser }}</td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-phone"></i> <strong>Téléphone</strong></td>
                                            <td>{{ $user->telephone }}</td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-envelope"></i> <strong>Email</strong></td>
                                            <td>{{ $user->emailuser }}</td>
                                        </tr>

                                        @if (in_array($role, ['client', 'coursier', 'livreur']))
                                            <tr>
                                                <td><i class="fas fa-birthday-cake"></i> <strong>Date de
                                                        naissance</strong></td>
                                                <td>{{ \Carbon\Carbon::parse($user->datenaissance)->format('d/m/Y') }}
                                                </td>
                                            </tr>
                                        @endif

                                        @if ($user->adresse)
                                            <tr>
                                                <td><i class="fas fa-map-marker-alt"></i> <strong>Adresse</strong>
                                                </td>
                                                <td>{{ $user->adresse->libelleadresse }}</td>
                                            </tr>
                                        @endif

                                        @if ($role === 'client')
                                            @if ($user->isUberClient())
                                                <tr>
                                                    <td><i class="fas fa-car"></i> <strong>Type de client</strong></td>
                                                    <td>Uber</td>
                                                </tr>
                                            @elseif ($user->isUberEatsClient())
                                                <tr>
                                                    <td><i class="fas fa-utensils"></i> <strong>Type de client</strong>
                                                    </td>
                                                    <td>Uber Eats</td>
                                                </tr>
                                            @endif
                                        @endif
                                    </tbody>
                                </table>

                                <div class="mt-4">
                                    <form action="{{ route('user.delete') }}" method="POST"
                                        onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-compte text-decoration-none">Supprimer mon
                                            compte
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarItems = document.querySelectorAll('#sidebar-menu .list-group-item[data-target]');
            const contentSections = document.querySelectorAll('.content-section');

            sidebarItems.forEach(item => {
                item.addEventListener('click', function() {
                    // Remove active class from all items and sections
                    sidebarItems.forEach(i => i.classList.remove('active'));
                    contentSections.forEach(section => section.classList.remove('active'));

                    // Add active class to clicked item and corresponding section
                    this.classList.add('active');
                    const targetId = this.getAttribute('data-target');
                    const targetSection = document.getElementById(targetId);
                    if (targetSection) {
                        targetSection.classList.add('active');
                    }
                });
            });
        });
    </script>
@endsection
