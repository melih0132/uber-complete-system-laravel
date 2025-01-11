@extends('layouts.app')

@section('title', 'Ajouter une bannière')

@section('content')
    <div class="container">
        <h1>Ajouter une bannière pour {{ $etablissement->nometablissement }}</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('etablissement.banner.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="etablissement_id" value="{{ $etablissement->idetablissement }}">

            <div class="form-group">
                <label for="banner_image">Image de la bannière</label>
                <input type="file" name="banner_image" id="banner_image" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Ajouter l'établissement</button>
        </form>
    </div>
@endsection
