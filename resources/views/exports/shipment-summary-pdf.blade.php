<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Shipment Summary Report</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }

        h2 {
            margin-bottom: 0;
        }

        .header {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Shipment Summary Report</h2>
        <p>Period: {{ $period->year ?? '-' }} | Printed at: {{ now()->format('d M Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Termin</th>
                <th>Shipment</th>
                <th>Vessel</th>
                <th>SPK</th>
                <th>Location</th>
                <th>Fuel</th>
                <th>Volume</th>
                <th>P</th>
                <th>A</th>
                <th>B</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($shipments as $index => $shipment)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ 'Termin ' . $shipment->termin->termin_number ?? '-' }}</td>
                    <td>{{ 'Shipment ' . $shipment->shipment_number ?? '-' }}</td>
                    <td>{{ $shipment->vessel->vessel_name ?? '-' }}</td>
                    <td>{{ $shipment->spk->spk_number ?? '-' }}</td>
                    <td>{{ $shipment->location }}</td>
                    <td>{{ $shipment->fuel->fuel_type ?? '-' }}</td>
                    <td>{{ number_format($shipment->volume, 0, ',', '.') }}</td>
                    <td>{{ number_format($shipment->p, 0, ',', '.') }}</td>
                    <td>{{ number_format($shipment->a, 0, ',', '.') }}</td>
                    <td>{{ number_format($shipment->b, 0, ',', '.') }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $shipment->status_shipment)) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
