@extends('layouts.app')

@section('title', 'Statistiques Mensuelles')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/serviceCourse.css') }}">
@endsection

@section('content')
    @if ($statSession == 'Course')
        <div class="container">
            <h1>Statistiques Mensuelles des Courses</h1>
        </div>
        <div class="chart-container" style="width: 80%; margin: 0 auto;">
            <canvas id="courseChart"></canvas>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('courseChart').getContext('2d');
            const data = {
                labels: {!! json_encode(
                    $statistiques->map(fn($stat) => \Carbon\Carbon::createFromFormat('m-Y', $stat->mois . '-' . $stat->annee)->format('F Y'))->toArray(),
                ) !!},
                datasets: [{
                    label: 'Nombre de Courses',
                    data: {!! json_encode($statistiques->pluck('total_courses')) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            };

            new Chart(ctx, {
                type: 'bar',
                data: data,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true, // Toujours commencer l'axe Y à 0
                            suggestedMax: 20 // Assure que la hauteur totale de l'axe Y soit d'au moins 100
                        }
                    }
                }
            });
        </script>
    @elseif ($statSession == 'Montant')
        <div class="container">
            <h1>Statistiques Mensuelles des Montants</h1>
        </div>
        <div class="chart-container" style="width: 80%; margin: 0 auto;">
            <canvas id="montantChart"></canvas>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('montantChart').getContext('2d');

            const data = {

                labels: ['Début', ...{!! json_encode(
                    $statistiques->map(fn($stat) => \Carbon\Carbon::createFromFormat('m-Y', $stat->mois . '-' . $stat->annee)->format('F Y'))->toArray(),
                ) !!}],
                datasets: [{
                    label: 'Montants des Courses (€)',
                    data: [0, ...{!! json_encode($statistiques->pluck('total_montant')->toArray()) !!}],
                    fill: false,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    tension: 0.1,
                    borderWidth: 2,
                }]
            };

            new Chart(ctx, {
                type: 'line',
                data: data,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            min: 0,
                            beginAtZero: true,
                        }
                    }
                }
            });
        </script>
    @elseif ($statSession == 'TypePrestation')
        @if ($statistiques->isEmpty())
            <p>Aucune donnée disponible pour afficher les statistiques.</p>
        @else
            <div class="container">
                <h1>Statistiques Mensuelles des Prestations</h1>
            </div>

            <div class="chart-container" style="width: 80%; margin: 0 auto;">
                <canvas id="PrestationChart"></canvas>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

            <script>
                const statistiques = {!! json_encode($statistiques) !!};
                const labels = {!! json_encode($labels) !!};


                const uberXData = statistiques.map(stat => stat.uberx !== undefined ? parseInt(stat.uberx, 10) || 0 : 0);
                const uberXLData = statistiques.map(stat => stat.uberxl !== undefined ? parseInt(stat.uberxl, 10) || 0 : 0);
                const uberVanData = statistiques.map(stat => stat.ubervan !== undefined ? parseInt(stat.ubervan, 10) || 0 : 0);
                const comfortData = statistiques.map(stat => stat.comfort !== undefined ? parseInt(stat.comfort, 10) || 0 : 0);
                const greenData = statistiques.map(stat => stat.green !== undefined ? parseInt(stat.green, 10) || 0 : 0);
                const uberPetData = statistiques.map(stat => stat.uberpet !== undefined ? parseInt(stat.uberpet, 10) || 0 : 0);
                const berlinData = statistiques.map(stat => stat.berlin !== undefined ? parseInt(stat.berlin, 10) || 0 : 0);


                const data = {
                    labels: labels,
                    datasets: [{
                            label: 'UberX',
                            data: uberXData,
                            backgroundColor: 'rgba(54, 162, 235, 0.5)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1,
                        },
                        {
                            label: 'UberXL',
                            data: uberXLData,
                            backgroundColor: 'rgba(255, 99, 132, 0.5)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1,
                        },
                        {
                            label: 'UberVan',
                            data: uberVanData,
                            backgroundColor: 'rgba(75, 192, 192, 0.5)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1,
                        },
                        {
                            label: 'Comfort',
                            data: comfortData,
                            backgroundColor: 'rgba(255, 159, 64, 0.5)',
                            borderColor: 'rgba(255, 159, 64, 1)',
                            borderWidth: 1,
                        },
                        {
                            label: 'Green',
                            data: greenData,
                            backgroundColor: 'rgba(75, 255, 75, 0.5)',
                            borderColor: 'rgba(75, 255, 75, 1)',
                            borderWidth: 1,
                        },
                        {
                            label: 'UberPet',
                            data: uberPetData,
                            backgroundColor: 'rgba(153, 102, 255, 0.5)',
                            borderColor: 'rgba(153, 102, 255, 1)',
                            borderWidth: 1,
                        },
                        {
                            label: 'Berline',
                            data: berlinData,
                            backgroundColor: 'rgba(255, 206, 86, 0.5)',
                            borderColor: 'rgba(255, 206, 86, 1)',
                            borderWidth: 1,
                        }
                    ]
                };

                // Configurer et initialiser le graphique
                const ctx = document.getElementById('PrestationChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: data,
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            }
                        },
                        interaction: {
                            intersect: false,
                        },
                        scales: {
                            x: {

                            },
                            y: {
                                suggestedMax: 5,
                                beginAtZero: true,
                            }
                        }
                    }
                });
            </script>
        @endif





    @elseif ($statSession == 'Geo')
    <div class="container">
        <h1>État des Montants des Courses par Ville</h1>
    </div>

    <div class="chart-container" style="width: 80%; margin: 0 auto;">
        <canvas id="MontantChart"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const statistiques = {!! json_encode($statistiques) !!};

        const villes = [...new Set(statistiques.map(stat => stat.ville))];
        const mois = [...new Set(statistiques.map(stat => `${stat.mois}-${stat.annee}`))];

        const couleursVilles = [
        'rgba(255, 99, 132, 0.5)', // Rouge clair
        'rgba(54, 162, 235, 0.5)', // Bleu clair
        'rgba(75, 192, 192, 0.5)', // Turquoise
        'rgba(255, 159, 64, 0.5)', // Orange clair
        'rgba(153, 102, 255, 0.5)', // Violet
        'rgba(75, 255, 75, 0.5)',  // Vert clair
        'rgba(255, 99, 71, 0.5)',  // Tomato
        'rgba(255, 165, 0, 0.5)',  // Orange
        'rgba(255, 215, 0, 0.5)',  // Jaune or
        'rgba(173, 216, 230, 0.5)', // Bleu pastel
        'rgba(138, 43, 226, 0.5)', // Bleu violet
        'rgba(139, 69, 19, 0.5)',  // Marron
    ];

        const data = villes.map(ville => {
            return {
                label: ville,
                data: mois.map(m => {
                    const stat = statistiques.find(s => `${s.mois}-${s.annee}` === m && s.ville === ville);
                    return stat ? stat.total_montant : 0;
                }),
                backgroundColor:    couleursVilles[couleursVilles.length],
                borderColor: couleursVilles[couleursVilles.length],
                borderWidth: 1
            };
        });

        const chartData = {
            labels: mois,
            datasets: data
        };

        const ctx = document.getElementById('MontantChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: chartData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>




    @endif
    <a href="javascript:history.back()" class="btn-back ">
        <span class="uber-btn mt-4">Retour</span>
    </a>
@endsection
