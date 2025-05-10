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
            display: block;
            margin: 0 auto;
        }
    </style>
</head>

<body>

    <!-- Espacio para membretado -->
    @if ($base64)
        <div style="text-align: center; margin-bottom: 20px;">
            <img src="{{ $base64 }}" style="max-width: 700px; height: auto;">
        </div>
    @endif

    <!-- Datos del Cliente -->
    <p class="section">Datos del Cliente</p>
    <table>
        <tr>
            <td style="vertical-align: middle; width: 50%; padding-right: 10px; border-collapse: collapse;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr style="background-color: #f9f9f9;">
                        <td style="border-bottom: 1px solid #ccc; padding: 8px; text-align: center;">
                            <strong>Cliente:</strong> {{ $cliente_nombre }}
                        </td>
                    </tr>
                    <tr style="background-color: #eaeaea;">
                        <td style="border-bottom: 1px solid #ccc; padding: 8px; text-align: center;">
                            <strong>At'n:</strong> {{ $usuario }} {{ $usuario_first_last_name }}
                            {{ $usuario_second_last_name }}
                        </td>
                    </tr>
                    <tr style="background-color: #f9f9f9;">
                        <td style="border-bottom: 1px solid #ccc; padding: 8px; text-align: center;">
                            <strong>E-mail:</strong> {{ $cliente_correo }}
                        </td>
                    </tr>
                    <tr style="background-color: #eaeaea;">
                        <td style="border-bottom: 1px solid #ccc; padding: 8px; text-align: center;">
                            <strong>Dirección:</strong> {{ $proyecto_direccion }}
                        </td>
                    </tr>
                    <tr style="background-color: #f9f9f9;">
                        <td style="padding: 8px; text-align: center;">
                            <strong>Teléfonos de contacto:</strong> {{ $cliente_contacto_1 }} -
                            {{ $cliente_telefono_1 }}<br>
                            @if ($cliente_contacto_2 !== 'No registrado' && $cliente_telefono_2 !== 'No registrado')
                                {{ $cliente_contacto_2 }} - {{ $cliente_telefono_2 }}
                            @endif
                        </td>
                    </tr>
                </table>
            </td>
            <td style="vertical-align: middle; width: 50%; border-collapse: collapse;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr style="background-color: #f4f4f4;">
                        <td style="border-bottom: 1px solid #ccc; padding: 8px; text-align: center;">
                            <strong style="font-size: 14px;">Proyecto: {{ $proyecto }}</strong>
                        </td>
                    </tr>
                    <tr style="background-color: #ffffff;">
                        <td style="border-bottom: 1px solid #ccc; padding: 8px; text-align: center;">
                            <strong>Tipo de Proyecto:</strong> {{ $proyecto_tipo }}
                        </td>
                    </tr>
                    <tr style="background-color: #f4f4f4;">
                        <td style="padding: 8px; text-align: center;">
                            <strong>Fecha:</strong> {{ $proyecto_fecha }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Suministro de Materiales -->
    <!-- Seccion de items registrados en el sistema -->
    <p class="section">Suministro de Materiales</p>
    @foreach ($items_cotizar_data as $item)
        @php
            $path = storage_path('app/public/' . str_replace('http://127.0.0.1:8000/storage/', '', $item['imagen']));
            $base64Item = null;

            if (file_exists($path)) {
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64Item = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
        @endphp

        <table style="width: 100%; border-collapse: collapse;">
            <tr class="image-item">
                <td colspan="3" style="text-align: center; padding: 10px;">
                    @if ($base64Item)
                        <img src="{{ $base64Item }}" style="max-width: 100px;">
                    @else
                        <p>⚠️ Imagen no disponible</p>
                    @endif
                    <p style="font-weight: bold; margin-top: 5px;">{{ $item['nombre'] }}</p>
                </td>
            </tr>
            <tr style="background-color: #dbeeff;">
                <th style="width: 20%; text-align: center; padding: 8px;">Cantidad</th>
                <th style="width: 50%; text-align: center; padding: 8px;">Descripción</th>
                <th style="width: 30%; text-align: center; padding: 8px;">Marca</th>
            </tr>
            <tr>
                <td style="text-align: center; padding: 8px;">{{ $item['cantidad'] }}</td>
                <td style="text-align: center; padding: 8px;">{{ $item['descripcion'] ?? 'Sin descripción' }}</td>
                <td style="text-align: center; padding: 8px;">{{ $item['marca'] }}</td>
            </tr>
        </table>
        <hr>
    @endforeach

    <!-- Seccion de items temporales que no estan registrados -->
    @if (!empty($items_cotizar_temporales_data))
        <p class="section">Ítems Especiales</p>
        @foreach ($items_cotizar_temporales_data as $item)
            <table style="width: 100%; border-collapse: collapse;">
                <tr style="background-color: #eaeaea;">
                    <th style="width: 25%; text-align: center; padding: 8px;">Nombre</th>
                    <th style="width: 25%; text-align: center; padding: 8px;">Descripción</th>
                    <th style="width: 25%; text-align: center; padding: 8px;">Cantidad</th>
                    <th style="width: 25%; text-align: center; padding: 8px;">Unidad</th>
                </tr>
                <tr>
                    <td style="text-align: center; padding: 8px;">{{ $item['nombre'] }}</td>
                    <td style="text-align: center; padding: 8px;">{{ $item['descripcion'] ?? 'Sin descripción' }}</td>
                    <td style="text-align: center; padding: 8px;">{{ $item['cantidad'] ?? 'No especificado' }}</td>
                    <td style="text-align: center; padding: 8px;">{{ $item['unidad'] ?? 'Unidad no especificada' }}
                    </td>
                </tr>
            </table>
            <hr>
        @endforeach
    @endif

    @if ($proyecto_tipo === 'Obra')
        <p class="section">Obra</p>

        <!-- Primera tabla alineada a la izquierda con nombres primero y valores a la derecha -->
        <table style="width: 48%; border-collapse: collapse; font-size: 10px; float: left;">
            <tr style="background-color: #f4f4f4;">
                <th style="width: 25%; text-align: left; padding: 4px;">Área Total</th>
                <td style="width: 25%; text-align: center; font-weight: bold; padding: 4px;">{{ $areasTotales }}</td>
                <th style="width: 25%; text-align: left; padding: 4px;">Frente</th>
                <td style="width: 25%; text-align: center; font-weight: bold; padding: 4px;">{{ $frentes }}</td>
            </tr>
            <tr style="background-color: #eaeaea;">
                <th style="text-align: left; padding: 4px;">Fondo</th>
                <td style="text-align: center; font-weight: bold; padding: 4px;">{{ $fondos }}</td>
                <th style="text-align: left; padding: 4px;">Altura Techo</th>
                <td style="text-align: center; font-weight: bold; padding: 4px;">{{ $alturasTecho }}</td>
            </tr>
            <tr style="background-color: #f4f4f4;">
                <th style="text-align: left; padding: 4px;">Altura Muros</th>
                <td style="text-align: center; font-weight: bold; padding: 4px;">{{ $alturasMuros }}</td>
                <td colspan="2"></td> <!-- Celda vacía para alineación -->
            </tr>
        </table>

        <!-- Segunda tabla alineada a la derecha con nombres primero y valores a la derecha -->
        <table style="width: 48%; border-collapse: collapse; font-size: 10px; float: right;">
            <tr style="background-color: #eaeaea;">
                <th style="width: 50%; text-align: left; padding: 4px;">Canalón</th>
                <td style="width: 50%; text-align: center; font-weight: bold; padding: 4px;">{{ $canalones }}</td>
            </tr>
            <tr style="background-color: #f4f4f4;">
                <th style="text-align: left; padding: 4px;">Perimetral</th>
                <td style="text-align: center; font-weight: bold; padding: 4px;">{{ $perimetrales }}</td>
            </tr>
            <tr style="background-color: #eaeaea;">
                <th style="text-align: left; padding: 4px;">Caballete</th>
                <td style="text-align: center; font-weight: bold; padding: 4px;">{{ $caballetes }}</td>
            </tr>
        </table>

        <div style="clear: both;"></div> <!-- Asegura que las tablas no causen desbordamiento -->

        <!-- Adicionales de la Obra -->
        <p class="section">Adicionales de la obra</p>

        @if (array_filter((array) json_decode(Session::get('estructuras', '[]'), true)) && array_filter((array) json_decode(Session::get('cantidades', '[]'), true)))
            <table>
                <tr style="background-color: #f4f4f4;">
                    <th style="width: 50%; text-align: center; padding: 8px;">Elementos</th>
                    <th style="width: 50%; text-align: center; padding: 8px;">Cantidades</th>
                </tr>
                @foreach ($estructuras as $index => $estructura)
                    <tr>
                        <td style="text-align: center; padding: 8px;">{{ $estructura }}</td>
                        <td style="text-align: center; padding: 8px;">{{ $cantidades[$index] ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </table>
        @else
            <p style="text-align: center; padding: 10px; font-style: italic;">Sin datos adicionales registrados</p>
        @endif

    @endif
    {{-- Pie de pagina de la Lista a cotizar --}}
    <div
        style="position: fixed; bottom: 10px; width: 100%; text-align: center; font-size: 8px; color: #555; line-height: 1.1;">
        <strong>Este documento contiene información privada, confidencial y privilegiada, pudiendo incluir secretos
            comerciales y/o industriales protegidos mediante la Ley Federal de la Propiedad Industrial.</strong><br>
        Nuestra marca, logotipos y slogan publicitarios están protegidos como derechos de autor ante el IMPI. Este
        mensaje está dirigido únicamente al destinatario.<br>
        Si usted no es la persona indicada, la lectura, uso, divulgación, reenvío o copia de esta información está
        estrictamente prohibida por la legislación vigente.<br>
        <strong>GTConstructions©, El soporte de tu obra©, Copyright todos los derechos reservados.</strong>
    </div>

</body>

</html>
