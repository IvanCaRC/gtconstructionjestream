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
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        .section {
            background-color: #f4f4f4;
            padding: 10px;
            font-size: 14px;
            font-weight: bold;
            border-bottom: 2px solid #333;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #eaeaea;
        }

        .highlight {
            font-weight: bold;
        }

        .signature {
            margin-top: 40px;
            text-align: center;
            font-size: 14px;
            font-weight: bold;
        }

        .signature-line {
            border-top: 1px solid #333;
            width: 200px;
            margin: 10px auto;
        }
    </style>
    <style>
        .signature-container {
            position: absolute;
            bottom: 40px;
            /* 🔹 Siempre al final del documento */
            width: 100%;
            text-align: center;
        }

        .signature-table {
            width: 100%;
            table-layout: fixed;
        }

        .signature-cell {
            width: 50%;
            padding-top: 40px;
            /* 🔹 Espacio antes de la línea */
            vertical-align: bottom;
            text-align: center;
        }

        .signature-line {
            border-top: 1px solid #333;
            width: 80%;
            height: 50px;
            /* 🔹 Más espacio para firmar */
            margin: 20px auto;
        }
    </style>
</head>

<body>

    @if ($base64)
        <div style="text-align: center; margin-bottom: 20px;">
            <img src="{{ $base64 }}" style="max-width: 700px; height: auto;">
        </div>
    @endif

    <p class="section">Orden de Venta</p>

    <table>
        <tr>
            <td class="highlight">No. de Orden:</td>
            <td>{{ $numeroOrden ?? '-' }}</td>
            <td class="highlight">Fecha de Emisión:</td>
            <td>{{ \Carbon\Carbon::now()->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td class="highlight">Cliente:</td>
            <td>{{ $cliente }}</td>
            <td class="highlight">Dirección:</td>
            <td>{{ $direccion }}</td>
        </tr>
    </table>

    @if (!empty($items_orden))
        <p class="section">Detalle de la Orden</p>
        <table>
            <tr>
                <th>Cantidad</th>
                <th>Nombre</th> <!-- 🔹 Agregamos la columna "Nombre" -->
                <th>Descripción</th>
                <th>Marca</th> <!-- 🔹 Nueva columna -->
                <th>Precio Unitario</th>
                <th>Precio</th> <!-- 🔹 Nueva columna -->
            </tr>
            @foreach ($items_orden as $item)
                <tr>
                    <td>{{ $item['cantidad'] }}</td>
                    <td>{{ $item['nombre'] }}</td> <!-- 🔹 Mostramos el nombre -->
                    <td>{{ $item['descripcion'] }}</td>
                    <td>{{ $item['marca'] }}</td> <!-- 🔹 Mostramos la marca -->
                    <td>${{ number_format($item['precio_unitario'], 2) }}</td>
                    <td>${{ number_format(($item['precio_unitario'] ?? 0) * ($item['cantidad'] ?? 1), 2) }}</td>
                    <!-- 🔹 Cálculo de precio total -->
                </tr>
            @endforeach
        </table>

        <p class="section">Resumen de Costos</p>
        <table>
            <tr style="background-color: #f4f4f4; font-weight: bold;">
                <td colspan="4"></td> <!-- Espacio vacío para alineación -->
                <td style="text-align: right;">Subtotal:</td>
                <td>${{ number_format($subtotal ?? 0, 2) }}</td>
            </tr>
            <tr style="background-color: #eaeaea; font-weight: bold;">
                <td colspan="4"></td> <!-- Espacio vacío para alineación -->
                <td style="text-align: right;">IVA (16%):</td>
                <td>${{ number_format($impuestos ?? 0, 2) }}</td>
            </tr>
            <tr style="background-color: #dbeeff; font-weight: bold;">
                <td colspan="4"></td> <!-- Espacio vacío para alineación -->
                <td style="text-align: right;">Total:</td>
                <td>${{ number_format($total ?? 0, 2) }}</td>
            </tr>
        </table>
    @endif

    <div class="signature-container">
        <table class="signature-table">
            <tr>
                <td class="signature-cell">
                    <p style="margin-bottom: 30px;">Firma del Cliente</p>
                    <div class="signature-line"></div>
                </td>
                <td class="signature-cell">
                    <p style="margin-bottom: 30px;">Firma del Responsable</p>
                    <div class="signature-line"></div>
                </td>
            </tr>
        </table>
    </div>

</body>

</html>
