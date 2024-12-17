<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture - {{ $reservation->idcourse }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .invoice-details {
            margin-bottom: 20px;
        }

        .invoice-details table {
            width: 100%;
            border-collapse: collapse;
        }

        .invoice-details th, .invoice-details td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .total {
            text-align: right;
            margin-top: 20px;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 0.9em;
            color: #777;
        }

        .row {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Facture - Course #{{ $reservation->idcourse }}</h1>

        <div class="row">
            <strong>Date de réservation :</strong> {{ \Carbon\Carbon::parse($reservation->datereservation)->format('d-m-Y') }}
            <br>
            <strong>Heure de réservation :</strong> {{ \Carbon\Carbon::parse($reservation->heurereservation)->format('H:i') }}
        </div>

        <div class="row">
            <strong>Adresse de départ :</strong> {{ $reservation->startAddress }}
            <br>
            <strong>Adresse d'arrivée :</strong> {{ $reservation->endAddress }}
        </div>

        <div class="row">
            <strong>Prestation :</strong> {{ $reservation->libelleprestation }}
            <br>
            <strong>Date de course :</strong> {{ \Carbon\Carbon::parse($reservation->datecourse)->format('d-m-Y') }}
            <br>
            <strong>Heure de course :</strong> {{ \Carbon\Carbon::parse($reservation->heurecourse)->format('H:i') }}
        </div>

        <div class="invoice-details">
            <h3>Détails de la course</h3>
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Quantité</th>
                        <th>Prix Unitaire (€)</th>
                        <th>Total (€)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Course</td>
                        <td>1</td>
                        <td>{{ number_format($reservation->prixcourse, 2, ',', ' ') }}</td>
                        <td>{{ number_format($reservation->prixcourse, 2, ',', ' ') }}</td>
                    </tr>
                    <tr>
                        <td>Distance</td>
                        <td>1</td>
                        <td>{{ number_format($reservation->distance, 2, ',', ' ') }} km</td>
                        <td>{{ number_format($reservation->distance * 1.00, 2, ',', ' ') }}</td> <!-- Exemple de tarif basé sur la distance -->
                    </tr>
                    <tr>
                        <td>Temps</td>
                        <td>1</td>
                        <td>{{ number_format($reservation->temps, 2, ',', ' ') }} min</td>
                        <td>{{ number_format($reservation->temps * 0.25, 2, ',', ' ') }}</td> <!-- Exemple de tarif basé sur le temps -->
                    </tr>
                    <tr>
                        <td><strong>Total</strong></td>
                        <td></td>
                        <td></td>
                        <td><strong>{{ number_format($reservation->prixcourse + $reservation->distance * 1.00 + $reservation->temps * 0.25, 2, ',', ' ') }} €</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="total">
            <h3>Total à payer : {{ number_format($reservation->prixcourse + $reservation->distance * 1.00 + $reservation->temps * 0.25, 2, ',', ' ') }} €</h3>
        </div>

        <div class="footer">
            <p>Merci de nous avoir choisis pour votre course !</p>
            <p>Adresse de l'entreprise, numéro de téléphone, etc.</p>
        </div>
    </div>
</body>
</html>
