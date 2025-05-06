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

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .section {
            border-bottom: 2px solid #333;
            margin-top: 20px;
            padding-bottom: 5px;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
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
            background-color: #f4f4f4;
        }

        .image-item {
            text-align: center;
        }

        img {
            max-width: 100px;
        }
    </style>
</head>

<body>
    <h1>{{ $title }}</h1>

    <p class="section">Información del Proyecto</p>
    <table>
        <tr>
            <th>Proyecto</th>
            <td>{{ $proyecto }}</td>
        </tr>
        <tr>
            <th>Tipo</th>
            <td>{{ $proyecto_tipo }}</td>
        </tr>
        <tr>
            <th>Fecha</th>
            <td>{{ $proyecto_fecha }}</td>
        </tr>
        <tr>
            <th>Atendido por</th>
            <td>{{ $usuario }} {{ $usuario_first_last_name }} {{ $usuario_second_last_name }}</td>
        </tr>
    </table>

    <p class="section">Datos del Cliente</p>
    <table>
        <tr>
            <th>Nombre</th>
            <td>{{ $cliente_nombre }}</td>
        </tr>
        <tr>
            <th>Correo</th>
            <td>{{ $cliente_correo }}</td>
        </tr>
        <tr>
            <th>Dirección</th>
            <td>{{ $cliente_direccion }}</td>
        </tr>
        <tr>
            <th>Contacto Principal</th>
            <td>{{ $cliente_contacto_1 }} - {{ $cliente_telefono_1 }}</td>
        </tr>
        <tr>
            <th>Contacto Adicional</th>
            <td>{{ $cliente_contacto_2 }} - {{ $cliente_telefono_2 }}</td>
        </tr>
    </table>

    <p class="section">Medidas del Proyecto</p>
    <table>
        <tr>
            <th>Frente</th>
            <td>{{ $frentes }}</td>
        </tr>
        <tr>
            <th>Fondo</th>
            <td>{{ $fondos }}</td>
        </tr>
        <tr>
            <th>Altura del Techo</th>
            <td>{{ $alturasTecho }}</td>
        </tr>
        <tr>
            <th>Área Total</th>
            <td>{{ $areasTotales }}</td>
        </tr>
        <tr>
            <th>Altura de los Muros</th>
            <td>{{ $alturasMuros }}</td>
        </tr>
        <tr>
            <th>Canalón</th>
            <td>{{ $canalones }}</td>
        </tr>
        <tr>
            <th>Perimetral</th>
            <td>{{ $perimetrales }}</td>
        </tr>
        <tr>
            <th>Caballete</th>
            <td>{{ $caballetes }}</td>
        </tr>
    </table>

    <p class="section">Elementos Adicionales y Cantidades</p>
    <table>
        <tr>
            <th>Descripción</th>
            <th>Cantidad</th>
        </tr>
        @if (!empty($estructura_cantidad) && is_array($estructura_cantidad))
            <table>
                <tr>
                    <th>Descripción</th>
                    <th>Cantidad</th>
                </tr>
                @foreach ($estructura_cantidad as $item)
                    <tr>
                        <td>{{ $item['estructura'] }}</td>
                        <td>{{ $item['cantidad'] }}</td>
                    </tr>
                @endforeach
            </table>
        @endif
    </table>

    <p class="section">Ítems a Cotizar</p>
    @foreach ($items_cotizar_data as $item)
        <table>
            <tr class="image-item">
                <td colspan="2">
                    @if ($item['imagen'])
                        <img src="{{ $item['imagen'] }}">
                    @else
                        <p>⚠️ Imagen no disponible</p>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Nombre</th>
                <td>{{ $item['nombre'] }}</td>
            </tr>
            <tr>
                <th>Marca</th>
                <td>{{ $item['marca'] }}</td>
            </tr>
            <tr>
                <th>Cantidad Solicitada</th>
                <td>{{ $item['cantidad'] }}</td>
            </tr>
        </table>
        <hr>
    @endforeach
</body>

</html>
