@extends('layouts.app')

@section('title', 'Dashboard Service Course')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/serviceCourse.css') }}">
@endsection

@section('content')
<div class="container">
    <h1>Courses Demandées</h1>
    <div class="table-container">
        <table class="uber-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Départ</th>
                    <th>Destination</th>
                    <th>Ville</th>
                    <th>Prix</th>
                    <th>Statut</th>
                    <th>Distance</th>
                    <th>Temps</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($courses as $course)
                    <tr>
                        <td>{{ $course->idreservation }}</td>
                        <td>{{ $course->libelle_idadresse }}</td>
                        <td>{{ $course->libelle_adr_idadresse }}</td>
                        <td>{{ $course->nomville}}</td>
                        <td>{{ $course->prixcourse }} €</td>
                        <td>
                            <span class="status {{ strtolower($course->statutcourse) }}">
                                {{ ucfirst($course->statutcourse) }}
                            </span>
                        </td>
                        <td>{{ $course->distance }} km</td>
                        <td>{{ $course->temps }} min</td>
                        <td>{{  \Carbon\Carbon::parse($course->datecourse)->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
