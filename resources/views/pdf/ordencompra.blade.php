@php
    $path = public_path('img/membretadoGTC.png');
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
@endphp

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Orden de Compra - {{ $proveedorNombre }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            width: 90%;
            margin: 0 auto;
        }

        .membrete {
            height: 120px;
        }

        /* Espacio reservado para el membrete */
        .title {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .details,
        .extra-info {
            margin-bottom: 15px;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid black;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .total {
            font-size: 18px;
            font-weight: bold;
            text-align: right;
            margin-top: 20px;
        }

        .signature {
            margin-top: 50px;
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="membrete">
            @if ($base64)
                <div style="text-align: center; margin-bottom: 20px;">
                    <img src="{{ $base64 }}" style="max-width: 700px; height: auto;">
                </div>
            @endif
        </div>

        <div class="title">Orden de Compra - {{ $proveedorNombre }}</div>

        <div class="details">
            <p><strong>Proveedor:</strong> {{ $proveedorNombre }}</p>
            <p><strong>Cliente:</strong> {{ $clienteNombre }}</p>
            <p><strong>RFC:</strong> {{ $clienteRFC }}</p>
            <p><strong>Correo Electrónico:</strong> {{ $clienteCorreo }}</p>
            <p><strong>Dirección de Entrega:</strong> {{ $direccionEntrega }}</p>
            <p><strong>Contacto:</strong> {{ $clienteContacto }}</p>
            <p><strong>Condiciones de Pago:</strong> Transferencia bancaria a 30 días</p>
            <p><strong>Fecha Estimada de Entrega:</strong> {{ date('d/m/Y', strtotime('+10 days')) }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Nombre del Ítem</th>
                    <th>Descripción</th>
                    <th>Cantidad</th>
                    <th>Unidad</th>
                    <th>Precio Unitario</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items_cotizacion as $item)
                    <tr>
                        <td>{{ $item['nombre'] }}</td>
                        <td>{{ $item['descripcion'] ?? 'Sin descripción' }}</td>
                        <td>{{ $item['cantidad'] }}</td>
                        <td>{{ $item['unidad'] ?? 'Unidad no especificada' }}</td>
                        <td>${{ number_format($item['precio'], 2) }}</td>
                        <td>${{ number_format($item['precio'] * $item['cantidad'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total">
            <p><strong>Total de la Orden: ${{ number_format($total, 2) }}</strong></p>
        </div>

        <div class="extra-info">
            <p><strong>Notas Especiales:</strong> Favor de confirmar disponibilidad antes de proceder con el envío.</p>
        </div>

        <div class="signature">
            <p>Firma Autorizada: __________________________</p>
            <p>Firma del Proveedor: __________________________</p>
        </div>
    </div>

</body>

</html>
