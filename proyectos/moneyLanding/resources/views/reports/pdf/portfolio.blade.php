<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px; border-bottom: 1px solid #e5e7eb; font-size: 12px; text-align: left; }
        h1 { font-size: 20px; }
    </style>
</head>
<body>
    <h1>Reporte de Cartera</h1>
    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Cliente</th>
                <th>Monto</th>
                <th>% Interés</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($loans as $loan)
                <tr>
                    <td>{{ $loan->code }}</td>
                    <td>{{ $loan->client?->name }}</td>
                    <td>${{ number_format($loan->principal, 2) }}</td>
                    <td>{{ $loan->interest_rate }}%</td>
                    @php $status = $loan->status instanceof \BackedEnum ? $loan->status->value : $loan->status; @endphp
                    <td>{{ ucfirst($status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
