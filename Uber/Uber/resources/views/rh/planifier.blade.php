@extends('layouts.app')

@section('title', 'Uber RH')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/entretien.blade.css') }}">
@endsection

@section('content')
    <div class="container mt-5">
        <div class="div-planifier d-flex flex-column justify-content-center align-content-center my-auto">
            <h1 class="text-center">Planifier un entretien</h1>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('entretiens.planifier', $entretien->identretien) }}" method="POST">
                @csrf
                <div>
                    <label for="idcoursier">Coursier :</label>
                    <input type="text" name="coursier" id="coursier" class="form-control w-50 mb-3"
                        value="{{ $entretien->coursier->nomuser }} {{ $entretien->coursier->prenomuser }}" disabled>
                    <input type="hidden" name="idcoursier" value="{{ $entretien->idcoursier }}">
                </div>

                <div>
                    <label for="dateentretien">Date de l'entretien :</label>
                    <input type="datetime-local" name="dateentretien" id="dateentretien" class="form-control w-50" required
                        min="<?php echo date('Y-m-d\TH:i'); ?>">
                </div>

                <input type="hidden" name="status" value="Planifié">
                <button type="submit" class="btn-entretien mt-3">Planifier</button>
            </form>

        </div>
    </div>
@endsection
