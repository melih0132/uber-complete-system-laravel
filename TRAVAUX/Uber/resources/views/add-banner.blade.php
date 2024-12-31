@extends('layouts.ubereats')

@section('title', 'Ajouter une bannière')

@section('content')

    <div class="container">
        <div class="add-form my-5">
            <h1>Ajouter une bannière pour "{{ $etablissement->nometablissement }}"</h1>

            <form action="{{ route('store.etablissement.banner') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="etablissement_id" value="{{ $etablissement->idetablissement }}">

                <label for="banner_image">Image de bannière :</label>
                <input type="file" id="banner_image" name="banner_image" accept="image/*" required>
                @error('banner_image')
                    <div class="error">{{ $message }}</div>
                @enderror

                <div class="d-flex justify-content-center mt-3">
                    <button class="btn-add" type="submit">Ajouter la bannière</button>
                </div>
            </form>
        </div>
    </div>

@endsection
