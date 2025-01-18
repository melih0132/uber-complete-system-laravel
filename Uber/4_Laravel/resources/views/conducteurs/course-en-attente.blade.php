@extends($layout)

@section('title', 'Coursier')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/coursier.blade.css') }}">
@endsection

@section('content')
    <section>
        <div class="container">
            <h1 class="mt-5">{{ $type === 'courses' ? 'Courses en attente :' : 'Livraisons en attente :' }}</h1>

            @if (session('success'))
                <div class="alert alert-success mt-3">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger mt-3">
                    {{ session('error') }}
                </div>
            @endif

            <div class="row my-5">
                @forelse ($tasks as $task)
                    <div class="col-md-6 mb-4 task-card" id="task-{{ $task->idreservation ?? $task->idcommande }}">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">
                                    N° {{ $type === 'courses' ? 'réservation' : 'commande' }} :
                                    <strong>{{ $task->idreservation ?? $task->idcommande }}</strong>
                                </h5>
                                <p class="card-text">
                                    <strong>Client :</strong> {{ $task->genreuser }} {{ $task->nomuser }}
                                    {{ $task->prenomuser }}<br>
                                    <strong>Adresse de départ :</strong> {{ $task->libelle_idadresse ?? 'Non spécifiée' }},
                                    {{ $task->nomville ?? 'Non spécifiée' }}<br>
                                    <strong>Adresse de destination :</strong>
                                    {{ $task->libelle_adr_idadresse ?? 'Non spécifiée' }},
                                    {{ $task->nomville ?? 'Non spécifiée' }}<br>
                                    <strong>Prix estimé :</strong> {{ $task->prixcourse ?? $task->prixcommande }} €<br>

                                    @if ($type === 'courses')
                                        <strong>Date :</strong>
                                        {{ $task->datecourse ? \Carbon\Carbon::parse($task->datecourse)->isoFormat('D MMMM YYYY') : 'Non spécifiée' }}<br>
                                        <strong>Heure :</strong>
                                        {{ $task->heurecourse ? \Carbon\Carbon::parse($task->heurecourse)->format('H:i') : 'Non spécifiée' }}<br>
                                        <strong>Distance :</strong> {{ $task->distance }} km<br>
                                        <strong>Temps estimé :</strong> {{ $task->temps }} minutes<br>
                                    @endif
                                </p>
                                <div class="d-flex justify-content-between">
                                    <form method="POST"
                                        action="{{ route($type === 'courses' ? 'coursier.courses.cancel' : 'coursier.livraisons.cancel', ['idreservation' => $task->idreservation ?? $task->idcommande]) }}"
                                        onsubmit="hideTask(event, {{ $task->idreservation ?? $task->idcommande }})">
                                        @csrf
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-times"></i> Refuser
                                        </button>
                                    </form>
                                    <form method="POST"
                                        action="{{ route($type === 'courses' ? 'coursier.courses.accept' : 'coursier.livraisons.accept', ['idreservation' => $task->idreservation ?? $task->idcommande]) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-check"></i> Accepter
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <p>Aucune tâche en attente pour le moment.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script>
        function hideTask(event, taskId) {
            event.preventDefault();

            // Cacher l'élément correspondant
            const taskElement = document.getElementById('task-' + taskId);
            if (taskElement) {
                taskElement.style.transition = "opacity 0.5s ease";
                taskElement.style.opacity = "0";
                setTimeout(() => taskElement.remove(), 500);
            }

            // Soumettre le formulaire après un léger délai
            setTimeout(() => event.target.submit(), 500);
        }
    </script>
@endsection
