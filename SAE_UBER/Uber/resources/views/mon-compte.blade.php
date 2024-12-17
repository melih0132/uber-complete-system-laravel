@extends('layouts.connexion')

@section('title', 'Mon compte')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/mon-compte.blade.css') }}">
@endsection

@section('content')
<div class="container mt-5">
    <h1>Mon compte</h1>
    <div class="row">
        <div class="col-md-3">
            <ul class="list-group">
                <li class="list-group-item active">Informations sur le compte</li>
                <li class="list-group-item">Sécurité</li>
                <li class="list-group-item">Confidentialité et données</li>
            </ul>
        </div>
        <div class="col-md-9">

            <div class="d-flex flex-column align-items-start">
                <img src="https://cdn-icons-png.flaticon.com/512/9706/9706583.png" alt="Avatar" class="rounded-circle" width="100" height="100" id="profileImage">
                <button class="btn btn-link modify-link mx-2" onclick="document.getElementById('fileInput').click()">Modifier</button>
                <input type="file" id="fileInput" style="display: none;" accept="image/*" onchange="changeProfileImage(event)">
            </div>
            <h2>Informations sur le compte</h2>
            <script>
                window.onload = function() {
                    const savedImage = localStorage.getItem('profileImage');
                    if (savedImage) {
                        document.getElementById('profileImage').src = savedImage;
                    } else {
                        document.getElementById('profileImage').src = "https://cdn-icons-png.flaticon.com/512/9706/9706583.png";
                    }
                }

                function changeProfileImage(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            document.getElementById('profileImage').src = e.target.result;
                            localStorage.setItem('profileImage', e.target.result);
                        };
                        reader.readAsDataURL(file);
                    }
                }
            </script>
@auth
<div class="font-weight-bold mx-4">
    <p>Prénom : {{ Auth::user()->prenomuser }}</p>
    <p>Nom : {{ Auth::user()->nomuser }}</p>
    <p>Numéro de téléphone : {{ Auth::user()->telephone }}</p>
    <p>Adresse e-mail : {{ Auth::user()->emailuser }}</p>
</div>
@else
<p>Veuillez vous connecter pour voir vos informations de compte.</p>
@endauth
        </div>
    </div>
</div>
@endsection
