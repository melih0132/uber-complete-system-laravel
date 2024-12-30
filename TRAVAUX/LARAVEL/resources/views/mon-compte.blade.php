@extends('layouts.app')

@section('title', 'Mon compte')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/mon-compte.blade.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endsection

@section('content')
    <div class="container my-5">
        <div class="account mt-5">
            <h1 class="mb-4 text-center">Mon compte</h1>
            <div class="row">
                <!-- Sidebar -->
                <div class="col-md-3">
                    <ul class="list-group shadow-sm">
                        <li class="list-group-item active">
                            <i class="fas fa-user me-2"></i> Informations sur le compte
                        </li>
                        @if ($role === 'client')
                            <li class="list-group-item">
                                <i class="fas fa-taxi me-2"></i> Courses
                            </li>
                            <a href="{{ url('/carte-bancaire') }}" class="text-decoration-none">
                                <li class="list-group-item rounded-0">
                                    <i class="fas fa-credit-card me-2"></i> Carte Bancaire
                                </li>
                            </a>
                            <a href="{{ url('/favoris') }}" class="text-decoration-none">
                                <li class="list-group-item rounded-0">
                                    <i class="fas fa-star me-2"></i> Lieux favoris
                                </li>
                            </a>
                        @endif
                        <li class="list-group-item">
                            <i class="fas fa-shield-alt me-2"></i> Sécurité
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-user-shield me-2"></i> Confidentialité et données
                        </li>
                    </ul>
                </div>

                <!-- Main Content -->
                <div class="col-md-9">
                    <div class="p-4">
                        <!-- Section de la photo de profil -->
                        @if ($role === 'client')
                            <div>
                                <img src="{{ $user->photoprofile ? asset('storage/' . $user->photoprofile) : 'https://institutcommotions.com/wp-content/uploads/2018/05/blank-profile-picture-973460_960_720-1.png' }}"
                                    alt="Photo de profil" class="pdp_picture" id="profileImage">
                                <form action="{{ route('update.profile.image') }}" method="POST"
                                    enctype="multipart/form-data" class="mt-3 ms-3">
                                    @csrf
                                    <label for="profile_image" class="link-photo">
                                        Modifier la photo
                                    </label>
                                    <input type="file" id="profile_image" name="profile_image" style="display: none;"
                                        accept="image/*" onchange="this.form.submit()">
                                </form>
                                @if ($errors->has('profile_image'))
                                    <div class="alert alert-danger mt-2">
                                        {{ $errors->first('profile_image') }}
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Informations sur le compte -->
                        <h2 class="h4 mt-4">Informations sur le compte</h2>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                @if (in_array($role, ['client', 'coursier']))
                                    <p><strong>Nom :</strong> {{ $user->prenomuser }} {{ $user->nomuser }}</p>
                                @else
                                    <p><strong>Nom :</strong> Aucun nom (utilisateur système)</p>
                                @endif
                            </div>
                            <div class="col-md-6">
                                @if (in_array($role, ['client', 'coursier']))
                                    <p><strong>Numéro de téléphone :</strong> {{ $user->telephone }}</p>
                                @else
                                    <p><strong>Adresse mail :</strong> {{ $user['email'] }}</p>
                                @endif
                            </div>
                            @if (in_array($role, ['client', 'coursier']))
                                <div class="col-md-6">
                                    <p><strong>Adresse mail :</strong> {{ $user->emailuser }}</p>
                                </div>
                            @endif
                            <div class="col-md-6">
                                <p><strong>Rôle :</strong>
                                    @if ($role === 'client')
                                        Client
                                    @elseif ($role === 'coursier')
                                        Coursier
                                    @else
                                        {{ ucfirst($role) }}
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                @if (in_array($role, ['coursier']))
                                    <p><strong>Véhicule :</strong> {{ $coursier->vehicule->modele ?? 'Non attribué' }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Statut de conduite -->
                        @if ($role === 'coursier')
                            <h2 class="h4 mt-4">Statut de conduite</h2>
                            <p>
                                @if ($canDrive)
                                    <span class="text-success"><i class="fas fa-check-circle me-2"></i> Vous pouvez
                                        conduire.</span>
                                @else
                                    <span class="text-danger"><i class="fas fa-times-circle me-2"></i> Vous ne pouvez pas
                                        conduire. Assurez-vous qu'un de vos véhicules soit validé.</span>
                                @endif
                            </p>
                        @endif

                        <!-- Informations sur les entretiens -->
                        @if ($role === 'coursier' && !$canDrive)
                            <h2 class="h4 mt-4">Entretien</h2>
                            <div class="table-responsive mt-3">
                                <table class="table">
                                    <thead class="table-uber">
                                        <tr>
                                            <th>Date</th>
                                            <th>Statut</th>
                                            <th>Résultat</th>
                                            <th>RDV logistique</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($entretiens as $entretien)
                                            <tr class="table-bordered">
                                                <td>
                                                    {{ $entretien->dateentretien ? $entretien->dateentretien->format('d/m/Y H:i') : 'N/A' }}
                                                </td>
                                                <td>{{ ucfirst($entretien->status) }}</td>
                                                <td>{{ $entretien->resultat ?? 'Non défini' }}</td>
                                                <td>
                                                    @if ($entretien->resultat === 'Retenu' && $entretien->rdvlogistiquedate && $entretien->rdvlogistiquelieu)
                                                        <p class="mb-0">
                                                            <strong>Date :</strong>
                                                            {{ $entretien->rdvlogistiquedate->format('d/m/Y H:i') }}
                                                        </p>
                                                        <p class="mb-0">
                                                            <strong>Lieu :</strong> {{ $entretien->rdvlogistiquelieu }}
                                                        </p>
                                                    @else
                                                        <span class="p-coursier">Non programmé</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">Aucun entretien trouvé.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
