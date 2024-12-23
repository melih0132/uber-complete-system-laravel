@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Planifier un entretien</h1>

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
            <div class="form-group">
                <label for="idcoursier">Coursier</label>
                <input type="text" name="coursier" id="coursier" class="form-control"
                    value="{{ $entretien->coursier->nomuser }} {{ $entretien->coursier->prenomuser }}" disabled>
                <input type="hidden" name="idcoursier" value="{{ $entretien->idcoursier }}">
            </div>

            <div class="form-group">
                <label for="dateentretien">Date de l'entretien</label>
                <input type="datetime-local" name="dateentretien" id="dateentretien" class="form-control" required>
            </div>

            <input type="hidden" name="status" value="Plannifié">

            <button type="submit" class="btn btn-primary">Planifier</button>
        </form>
    </div>
@endsection
