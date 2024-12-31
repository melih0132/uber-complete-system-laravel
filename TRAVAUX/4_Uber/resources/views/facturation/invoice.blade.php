<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture Uber</title>
    <style>
        body {
            font-family: UberMove, UberMoveText, system-ui, "Helvetica Neue", Helvetica, Arial, sans-serif;
            margin: 20px;
            color: #333;
            background-color: #f9f9f9;
            line-height: 1.6;
        }

        h1,
        h2,
        h3 {
            margin: 0;
            color: #333;
        }

        h1 {
            font-size: 28px;
            margin-bottom: 5px;
        }

        .header,
        .footer {
            text-align: center;
            margin-bottom: 20px;
        }

        .header {
            padding-bottom: 10px;
        }

        .header h1 {
            color: #000;
        }

        .header p {
            margin: 5px 0;
            font-size: 14px;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        table th,
        table td {
            padding: 12px 15px;
            text-align: left;
        }

        th {
            background-color: #333;
            color: #fff;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f7f7f7;
        }

        tr:hover {
            background-color: #eaeaea;
        }

        .total {
            text-align: right;
            font-weight: bold;
            margin-top: 20px;
            font-size: 16px;
            color: #000;
        }

        .footer {
            font-size: 12px;
            color: #7f8c8d;
            margin-top: 40px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Facture Uber</h1>
        <p>Coursier : <strong>{{ $coursier->nomuser }} {{ $coursier->prenomuser }}</strong></p>
        <p>Période : <strong>{{ $start_date }}</strong> au <strong>{{ $end_date }}</strong></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID Course</th>
                <th>Date</th>
                <th>Prix (€)</th>
                <th>Pourboire (€)</th>
                <th>Distance (km)</th>
                <th>Temps (min)</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($trips as $trip)
                <tr>
                    <td>{{ $trip->idcourse }}</td>
                    <td>{{ \Carbon\Carbon::parse($trip->datecourse)->format('d/m/Y') }}</td>
                    <td>{{ number_format($trip->prixcourse, 2) }}</td>
                    <td>{{ number_format($trip->pourboire, 2) }}</td>
                    <td>{{ number_format($trip->distance, 2) }}</td>
                    <td>{{ $trip->temps }}</td>
                    <td>
                        @if ($trip->statutcourse == 'Terminée')
                            <span>Terminée</span>
                        @elseif ($trip->statutcourse == 'Annulée')
                            <span>Annulée</span>
                        @else
                            <span>{{ $trip->statutcourse }}</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        <h4 class="ms-4">Total : <span>{{ number_format($totalAmount, 2) }}€</span></h4>
    </div>

    <div class="footer">
        <p>Facture générée automatiquement par Uber. Merci pour votre confiance.</p>
        <p>Pour toute question, contactez-nous à <strong>support@uber.com</strong>.</p>
    </div>
</body>

</html>
