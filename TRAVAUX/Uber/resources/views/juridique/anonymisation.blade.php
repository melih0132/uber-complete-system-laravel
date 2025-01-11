@extends('layouts.app')

@section('title', 'Anonymisation | Uber')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/app.blade.css') }}">
@endsection

@section('content')
    <div class="container">
        <h1>Anonymisation des données des clients</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('juridique.anonymiser') }}" method="POST">
            @csrf
            <table class="table">
                <thead>
                    <tr>
                        <th>Sélectionner</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Date d'inscription</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clients as $client)
                        <tr>
                            <td>
                                <input type="checkbox" name="client_ids[]" value="{{ $client->id }}">
                            </td>
                            <td>{{ $client->name }}</td>
                            <td>{{ $client->email }}</td>
                            <td>{{ $client->phone }}</td>
                            <td>{{ $client->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <button type="submit" class="btn btn-danger">Anonymiser les données sélectionnées</button>
        </form>
    </div>
@endsection
