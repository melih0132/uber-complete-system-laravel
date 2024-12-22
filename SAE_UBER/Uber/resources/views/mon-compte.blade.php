@extends('layouts.app')

@section('title', 'Mon compte')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/mon-compte.blade.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endsection

@section('content')
    <div class="container">
        <div class="account mt-5">
            <h1 class="mb-4 text-center">Mon compte</h1>
            <div class="row">
                <!-- Sidebar -->
                <div class="col-md-3">
                    <ul class="list-group shadow-sm">
                        <li class="list-group-item active">
                            <i class="fas fa-user me-2"></i> Informations sur le compte
                        </li>
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
                    <div class="card p-4">
                        <!-- Section de la photo de profil -->
                        <div>
                            <img src="{{ asset($user->photoprofile ? 'storage/' . $user->photoprofile : 'https://institutcommotions.com/wp-content/uploads/2018/05/blank-profile-picture-973460_960_720-1.png') }}"
                                alt="Photo de profil" class="pdp_picture" id="profileImage">
                            <form action="{{ route('update.profile.image') }}" method="POST" enctype="multipart/form-data"
                                class="mt-3 ms-3">
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

                        <!-- Informations sur le compte -->
                        <h2 class="h4 mt-4">Informations sur le compte</h2>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <p><strong>Nom :</strong> {{ $user->prenomuser }} {{ $user->nomuser }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Numéro de téléphone :</strong> {{ $user->telephone }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Adresse e-mail :</strong> {{ $user->emailuser }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Rôle :</strong> {{ $role === 'client' ? 'Client' : 'Coursier' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
