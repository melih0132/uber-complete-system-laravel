<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Règlement Salaire Uber - {{ $coursier->nomuser }} {{ $coursier->prenomuser }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            line-height: 1.6;
            font-size: 12px;
        }

        .container {
            width: 100%;
            padding: 20mm;
            box-sizing: border-box;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 20px;
            margin: 0;
            padding: 0;
        }

        .header p {
            font-size: 14px;
            margin: 5px 0;
        }

        .section {
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 1px solid #333;
            padding-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 12px;
        }

        table th,
        table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }

        table th {
            background-color: #f7f7f7;
            font-weight: bold;
        }

        .total {
            font-size: 14px;
            font-weight: bold;
            text-align: right;
            margin-top: 30px;
        }

        .footer {
            text-align: center;
            font-size: 10px;
            color: #666;
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #000;
        }

        .footer a {
            color: #007BFF;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header Section -->
        <div class="header">
            <h1>Règlement Salaire</h1>
            <p><strong>Uber</strong></p>
            <p><strong>Coursier :</strong> {{ $coursier->prenomuser }} {{ $coursier->nomuser }}</p>
            <p><strong>Période :</strong> Du {{ $start_date }} au {{ $end_date }}</p>
        </div>

        <!-- Résumé Section -->
        <div class="section">
            <div class="section-title">Résumé</div>
            <table>
                <tr>
                    <th>Total Brut (€)</th>
                    <td>{{ number_format($totalGrossAmount, 2, ',', ' ') }}</td>
                </tr>
                <tr>
                    <th>Frais Uber (20%) (€)</th>
                    <td>{{ number_format($uberFees, 2, ',', ' ') }}</td>
                </tr>
                <tr>
                    <th>Total Net (€)</th>
                    <td>{{ number_format($totalNetAmount, 2, ',', ' ') }}</td>
                </tr>
            </table>
        </div>

        <!-- Détails des Courses -->
        <div class="section">
            <div class="section-title">Détails des Courses (10 premières)</div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Prix (€)</th>
                        <th>Pourboire (€)</th>
                        <th>Distance (km)</th>
                        <th>Temps (min)</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($trips->take(10) as $trip)
                    <tr>
                        <td>{{ $trip->idcourse }}</td>
                        <td>{{ \Carbon\Carbon::parse($trip->datecourse)->format('d/m/Y') }}</td>
                        <td>{{ number_format($trip->prixcourse, 2, ',', ' ') }}</td>
                        <td>{{ number_format($trip->pourboire, 2, ',', ' ') }}</td>
                        <td>{{ number_format($trip->distance, 2, ',', ' ') }}</td>
                        <td>{{ $trip->temps }}</td>
                        <td>{{ $trip->statutcourse }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Total Section -->
        <div class="total">
            <p>Total à Régler : {{ number_format($totalNetAmount, 2, ',', ' ') }} €</p>
        </div>

        <!-- Footer Section -->
        <div class="footer">
            <p>Facture générée automatiquement par Uber. Merci pour votre confiance.</p>
            <p>Pour toute question, contactez-nous à <a href="mailto:support@uber.com">support@uber.com</a>.</p>
        </div>
    </div>
</body>

</html>
