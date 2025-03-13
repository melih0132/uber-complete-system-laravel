@extends('layouts.app')

@section('title', 'Inscription')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/interface-connexion.blade.css') }}">
@endsection

@section('content')

    <div class="container">
        <div class="row justify-content-between mt-5">
            <div class="col-12 col-sm-6 interface d-flex align-items-center">
                <a href="{{ url('/interface-inscription-coursier') }}"" class="text-decoration-none">
                    <div class="bloc-interface">
                        <h2>Inscrivez-vous pour conduire ou livrer</h2>
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none">
                            <path d="m22.2 12-6.5 9h-3.5l5.5-7.5H2v-3h15.7L12.2 3h3.5l6.5 9Z" fill="currentColor"></path>
                        </svg>
                    </div>
                </a>
            </div>
            <div class="col-12 col-sm-6 interface d-flex align-items-center">
                <a href="{{ url('/register/passenger') }}" class="text-decoration-none">
                    <div class="bloc-interface">
                        <h2>Voyagez avec un compte passager</h2>
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none">
                            <path d="m22.2 12-6.5 9h-3.5l5.5-7.5H2v-3h15.7L12.2 3h3.5l6.5 9Z" fill="currentColor"></path>
                        </svg>
                    </div>
                </a>
            </div>
            <div class="col-12 col-sm-6 interface d-flex align-items-center">
                <a href="{{ url('/register/eats') }}" class="text-decoration-none">
                    <div class="bloc-interface">
                        <h2>Faites-vous livrer avec Uber Eats</h2>
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none">
                            <path d="m22.2 12-6.5 9h-3.5l5.5-7.5H2v-3h15.7L12.2 3h3.5l6.5 9Z" fill="currentColor"></path>
                        </svg>
                    </div>
                </a>
            </div>
            <div class="col-12 col-sm-6 interface d-flex align-items-center">
                <a href="{{ url('/interface-inscription-manager') }}" class="text-decoration-none">
                    <div class="bloc-interface">
                        <h2>Créez un compte pour la restauration</h2>
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none">
                            <path d="m22.2 12-6.5 9h-3.5l5.5-7.5H2v-3h15.7L12.2 3h3.5l6.5 9Z" fill="currentColor"></path>
                        </svg>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection
