@extends('layouts.app')

@section('title', 'Analyses et Performances')

@section('content')
    <h1>Analyses et Performances</h1>
    <h2>Statistiques Mensuelles</h2>
    <ul>
        <li>Site Value ? : {{ $performances }} €</li>
    </ul>
@endsection
