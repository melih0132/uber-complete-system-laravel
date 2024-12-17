@extends('layouts.app')

@section('title', 'Connexion')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth.blade.css') }}">

@endsection

@section('content')

    <div class="container1 mt-5">
        <h1 class="text-center fw-bold">Connexion</h1>

        <form method="post" action="{{ route('login') }}">
            @csrf
            <div class="d-flex justify-content-center align-items-center flex-column">
                <input type="text" name="emailuser" placeholder="Email" required class="mb-3" />
                <input type="password" name="motdepasseuser" placeholder="Password" required class="mb-3" />
                <select name="role" required class="mb-3">
                    <option value="" disabled selected>Choisissez un rôle</option>
                    <option value="client">Client</option>
                    <option value="coursier">Coursier</option>
                </select>
            </div>
            <div class="d-flex justify-content-center align-items-center flex-column">
                <button type="submit" class="btn btn-login">Connexion</button>
                <a href="{{ url('/register') }}" class="login-link my-3">Créer un compte</a>
            </div>
        </form>
    </div>

@endsection
