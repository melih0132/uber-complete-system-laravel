<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('invoice.title') }} N°{{ $idcourse }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.blade.css') }}">
    <style>
        body {

            color: #333;
        }

        .title {
            font-size: 20px;
            color: green;
            font-weight: bold;
        }

        .divider {
            border-top: 2px solid #28a745;
            margin: 10px 0;
        }

        .section-title {
            font-weight: bold;
            margin-top: 20px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
        }

        .total-section {
            text-align: right;
            font-size: 14px;
        }

        .grand-total {
            font-size: 16px;
            font-weight: bold;
        }

        table th,
        table td {
            text-align: center;
            vertical-align: middle;
        }

        table td:nth-child(2),
        table th:nth-child(2) {
            text-align: left;
            padding-left: 8px;
        }

        .logo {
            width: 150px;
            height: auto;
            display: block;
        }

        .logo-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100px;
        }

        .ltr {
            direction: ltr;
            display: inline-block;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <div class="title">
            {{ __('invoice.title') }}
            <span class="text-secondary">N°{{ $idcourse }}</span>
        </div>
        <div class="divider"></div>

        <div class="logo-container">
            <img src="img/Uber_logo_2018.png" alt="Uber_logo" class="logo">
        </div>

        <div class="d-flex justify-content-end">
            <div style="text-align: right;">
                <div class="section-title">{{ __('invoice.supplier') }}</div>
                <p style="line-height: 1.5;">
                    <strong>{{ __('invoice.supplier_company') }}</strong><br>
                    {{ __('invoice.supplier_team') }}<br>
                    {{ __('invoice.supplier_country') }}: France, {{ __('invoice.supplier_city') }}: Annecy<br>
                    {{ __('invoice.supplier_address') }}<br>
                    74000 - FRANCE<br>
                    SIRET : 517155028
                </p>
            </div>
        </div>

        <div class="section-title">{{ __('invoice.client') }}</div>
        <br>
        <div class="info-row">
            <span><strong>{{ __('invoice.invoice_date') }}:</strong> 55</span>
        </div>
        <div class="info-row">
            <span><strong>{{ __('invoice.emission_time') }}:</strong> 99</span>
        </div>
        <div class="info-row">
            <span><strong>{{ __('invoice.reservation_date') }}:</strong>
                {{ \Carbon\Carbon::parse($datereservation)->format('d-m-Y') }}</span>
        </div>
        <div class="info-row">
            <span><strong>{{ __('invoice.reservation_time') }}:</strong>
                {{ \Carbon\Carbon::parse($heurereservation)->format('H:i') }}</span>
        </div>
        <div class="info-row">
            <span><strong>{{ __('invoice.course_date') }}:</strong>
                {{ $datecourse }}</span>
        </div>
        <div class="info-row">
            <span><strong>{{ __('invoice.course_time') }}:</strong>
                {{ \Carbon\Carbon::parse($heurecourse)->format('H:i') }}</span>
        </div>
        <div class="info-row">
            <span><strong>{{ __('invoice.payment_method') }}:</strong> Carte</span>
        </div>
        <div class="info-row">
            <span><strong>{{ __('invoice.prestation') }}:</strong> {{ $libelleprestation }}</span>
        </div>

        <div class="d-flex justify-content-end">
            <div style="text-align: right;">
                <div class="section-title">{{ __('invoice.information') }}</div>
                <p style="line-height: 1.5;">
                    <strong>{{ __('invoice.start_address') }}:</strong> {{ $startAddress }}
                    <br>
                    <strong>{{ __('invoice.end_address') }}:</strong> {{ $endAddress }}
                </p>
            </div>
        </div>

        <table class="table table-bordered mt-4">
            <thead style="background-color: #f8f9fa;">
                <tr>
                    <th style="width: 5%;">{{ __('invoice.table_number') }}</th>
                    <th style="width: 40%;">{{ __('invoice.table_description') }}</th>
                    <th style="width: 15%;">{{ __('invoice.table_unit_price') }}</th>
                    <th style="width: 10%;">{{ __('invoice.table_vat_rate') }}</th>
                    <th style="width: 10%;">{{ __('invoice.table_vat_amount') }}</th>
                    <th style="width: 15%;">{{ __('invoice.table_total_ht') }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>{{ __('invoice.service_transport') }}</td>
                    <td>{{ number_format($prixcourse, 2, ',', ' ') }} {{ $monnaie }}</td>
                    <td>{{ $pourcentagetva }}%</td>
                    <td>{{ number_format(($prixcourse * $pourcentagetva) / 100, 2, ',', ' ') }} {{ $monnaie }}
                    </td>
                    <td>{{ number_format($prixcourse, 2, ',', ' ') }} {{ $monnaie }}</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>{{ __('invoice.tipping_service') }}</td>
                    <td>{{ number_format($pourboire, 2, ',', ' ') }} {{ $monnaie }}</td>
                    <td>No</td>
                    <td>0 {{ $monnaie }}</td>
                    <td>{{ number_format($pourboire, 2, ',', ' ') }} {{ $monnaie }}</td>
                </tr>
            </tbody>
        </table>

        <div class="row mt-3">
            <div class="col-md-8"></div>
            <div class="col-md-4 total-section">
                <p>{{ __('invoice.total_ht') }} : <strong>{{ number_format($prixcourse + $pourboire, 2, ',', ' ') }}
                        {{ $monnaie }}</strong></p>
                <p>{{ __('invoice.vat_amount') }}:
                    <strong>{{ number_format(($prixcourse * 20) / 100, 2, ',', ' ') }} {{ $monnaie }}</strong>
                </p>
                <div class="divider"></div>
                <p class="grand-total">
                    {{ __('invoice.invoice_total') }}:
                    <span class="ltr">
                        {{ number_format(($prixcourse * 20) / 100 + $prixcourse + $pourboire, 2, ',', ' ') }}
                        {{ $monnaie }}
                    </span>
                </p>
            </div>
        </div>
    </div>
</body>

</html>
