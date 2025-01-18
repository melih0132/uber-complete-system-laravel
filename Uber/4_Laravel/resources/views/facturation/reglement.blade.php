<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Règlement Salaire Uber - {{ $coursier->nomuser }} {{ $coursier->prenomuser }}</title>
    <style>
        body {

            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            font-size: 14px;
        }

        .container {
            margin: auto;
            max-width: 900px;
            padding: 20px;
        }

        .header {
            text-align: center;
            padding: 10px 0;
        }

        .header h1 {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
            color: #28a745;
        }

        .header p {
            margin: 5px 0;
            color: #555;
        }

        .divider {
            border-top: 2px solid #28a745;
            margin: 20px 0;
        }

        .section-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        table th {
            background-color: #f1f1f1;
            font-weight: bold;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .total-section {
            text-align: right;
            font-size: 16px;
            font-weight: bold;
            margin-top: 20px;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #555;
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
        }

        .footer a {
            color: #28a745;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        .logo {
            width: 150px;
            height: auto;
            display: block;
            margin: 0 auto 20px auto;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header Section -->
        <div class="header">
            <img src="img/Uber_logo_2018.png" alt="Uber Logo" class="logo">
            <h1>Règlement Salaire</h1>
            <p><strong>Uber</strong></p>
            <p><strong>Coursier :</strong> {{ $coursier->prenomuser }} {{ $coursier->nomuser }}</p>
            <p><strong>Période :</strong> Du {{ $start_date }} au {{ $end_date }}</p>
        </div>

        <div class="divider"></div>

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

        <div class="divider"></div>

        <!-- Détails des Courses -->
        <div class="section">
            <div class="section-title">Détails des Courses</div>
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
        <div class="total-section">
            <p>Total à Régler : {{ number_format($totalNetAmount + $totalGrossAmountTips, 2, ',', ' ') }} €</p>
        </div>

        <!-- Footer Section -->
        <div class="footer">
            <p>Facture générée automatiquement par Uber. Merci pour votre travail.</p>
            <p>Pour toute question, contactez-nous à <a href="mailto:support@uber.com">support@uber.com</a>.</p>
        </div>
    </div>
</body>

</html>
