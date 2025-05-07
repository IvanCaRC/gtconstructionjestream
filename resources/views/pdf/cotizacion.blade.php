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
            text-transform: uppercase;
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

        .data-table td {
            background-color: #f9f9f9;
        }

        .highlight {
            font-weight: bold;
            color: #333;
        }
    </style>
</head>

<body>

    <!-- Espacio para el membrete -->
    @if ($base64)
        <div style="text-align: center; margin-bottom: 20px;">
            <img src="{{ $base64 }}" style="max-width: 700px; height: auto;">
        </div>
    @endif

    {{-- <h1>{{ $title }}</h1> --}}

    <!-- Información de la empresa y cotización -->
    <p class="section">Información de la Empresa</p>
    <table class="data-table">
        <tr>
            <td class="highlight">Correo:</td>
            <td>administración@gtcgroup.com.mx</td>
            <td class="highlight">Teléfono:</td>
            <td>+52 9513791159</td>
        </tr>
        <tr>
            <td class="highlight">Dirección:</td>
            <td>Veracruz, Veracruz</td>
            <td class="highlight">Sitio Web:</td>
            <td><a href="https://www.gtcgroup.com.mx">www.gtcgroup.com.mx</a></td>
        </tr>
        <tr>
            <td class="highlight">Horario de Atención:</td>
            <td colspan="3">Lunes a Viernes de 9:00 a 18:00 hrs</td>
        </tr>
    </table>

    <p class="section">Datos de la Cotización</p>
    <table class="data-table">
        <tr>
            <td class="highlight">No. de Cotización:</td>
            <td>{{ $cotizacion->id ?? '-' }}</td>
            <td class="highlight">Fecha de Emisión:</td>
            <td>{{ \Carbon\Carbon::now()->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td class="highlight">Válido Hasta:</td>
            <td>{{ \Carbon\Carbon::now()->addDays(30)->format('d/m/Y') }}</td>
            <td class="highlight">Atendido por:</td>
            <td>{{ $usuario_atendio }}</td>
        </tr>
        <tr>
            <td class="highlight">Cliente:</td>
            <td>{{ $cliente }}</td>
            <td class="highlight">Dirección:</td>
            <td>{{ $direccion }}</td>
        </tr>
        <tr>
            <td class="highlight">Proyecto:</td>
            <td>{{ $proyecto }}</td>
            <td class="highlight">Tipo de Proyecto:</td>
            <td>{{ $tipo_proyecto }}</td>
        </tr>
    </table>

    <!-- Tabla de Ítems -->
    @if (!empty($items_cotizacion))
        <p class="section">Ítems Cotizados</p>
        <table>
            <tr>
                <th>Cantidad</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio Unitario</th>
            </tr>
            @foreach ($items_cotizacion as $item)
                <tr>
                    <td>{{ $item['cantidad'] ?? '-' }}</td>
                    <td>{{ $item['nombre'] ?? '-' }}</td>
                    <td>{{ $item['descripcion'] ?? '-' }}</td>
                    <td>${{ number_format($item['precio'] ?? 0, 2) }}</td>
                </tr>
            @endforeach

            <!-- ✅ Nueva fila para Resumen de Costos dentro de la tabla de ítems -->
            <tr style="background-color: #f4f4f4; font-weight: bold;">
                <td colspan="2"></td> <!-- Espacio vacío para alineación -->
                <td style="text-align: right;">Subtotal:</td>
                <td>${{ number_format($subtotal ?? 0, 2) }}</td>
            </tr>
            <tr style="background-color: #eaeaea; font-weight: bold;">
                <td colspan="2"></td> <!-- Espacio vacío para alineación -->
                <td style="text-align: right;">IVA:</td>
                <td>${{ number_format($impuestos ?? 0, 2) }}</td>
            </tr>
            <tr style="background-color: #dbeeff; font-weight: bold;">
                <td colspan="2"></td> <!-- Espacio vacío para alineación -->
                <td style="text-align: right;">Total:</td>
                <td>${{ number_format($total ?? 0, 2) }}</td>
            </tr>
        </table>
    @endif

</body>

</html>
