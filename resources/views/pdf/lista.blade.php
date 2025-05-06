<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
        }

        h1 {
            text-align: center;
            color: #333;
        }
    </style>
</head>

<body>
    <h1>{{ $title }}</h1>
    <p><strong>Proyecto:</strong> {{ $proyecto }}</p>
    <p><strong>Tipo:</strong> {{ $proyecto_tipo }}</p>
    <p><strong>Fecha:</strong> {{ $proyecto_fecha }}</p>
    <p><strong>Atentido por:</strong> {{ $usuario }} {{ $usuario_first_last_name }} {{ $usuario_second_last_name }}
    </p>
    <p><strong>Cliente:</strong> {{ $cliente_nombre }}</p>
    <p><strong>Correo:</strong> {{ $cliente_correo }}</p>
    <p><strong>Dirección:</strong> {{ $cliente_direccion }}</p>
    <p><strong>Contacto Principal:</strong> {{ $cliente_contacto_1 }} - {{ $cliente_telefono_1 }}</p>
    <p><strong>Contacto adicional:</strong> {{ $cliente_contacto_2 }} - {{ $cliente_telefono_2 }}</p>
    <p><strong>Frente:</strong> {{ $frentes }}</p>
    <p><strong>Fondo:</strong> {{ $fondos }}</p>
    <p><strong>Altura del Techo:</strong> {{ $alturasTecho }}</p>
    <p><strong>Área Total:</strong> {{ $areasTotales }}</p>
    <p><strong>Altura de los Muros:</strong> {{ $alturasMuros }}</p>
    <p><strong>Canalón:</strong> {{ $canalones }}</p>
    <p><strong>Perimetral:</strong> {{ $perimetrales }}</p>
    <p><strong>Caballete:</strong> {{ $caballetes }}</p>
    {{-- <p><strong>Elementos:</strong> {{ $estructuras }}</p>
    <p><strong>Cantidades:</strong> {{ $cantidades }}</p> --}}
    <p><strong>Elementos Adicionales/Cantidad:</strong> {{ $estructura_cantidad }}</p>
    {{-- <p><strong>Ítems a Cotizar:</strong> {{ $items_cotizar }}</p> --}}
    @foreach ($items_cotizar_data as $item)
        @php
            // Extraer solo la parte relevante de la ruta
            $path = storage_path('app/public/' . str_replace('http://127.0.0.1:8000/storage/', '', $item['imagen']));

            // Verificamos que el archivo realmente existe antes de procesarlo
            $base64Item = null;
            if (file_exists($path)) {
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64Item = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
        @endphp

        {{-- <p><strong>Ruta generada:</strong> {{ $item['imagen'] }}</p>
        <p><strong>Ruta procesada para Base64:</strong> {{ $path }}</p> --}}

        @if ($base64Item)
            <p><strong>Imagen del Ítem:</strong></p>
            <img src="{{ $base64Item }}">
        @else
            <p>⚠️ Imagen no disponible</p>
        @endif

        <p><strong>Nombre:</strong> {{ $item['nombre'] }}</p>
        <p><strong>Marca:</strong> {{ $item['marca'] }}</p>
        <p><strong>Cantidad Solicitada:</strong> {{ $item['cantidad'] }}</p>
        <hr>
    @endforeach
    {{-- <p><strong>Ítems Temporales:</strong> {{ $items_cotizar_temporales }}</p> --}}
    @foreach ($items_cotizar_temporales_data as $item)
        <p><strong>Nombre:</strong> {{ $item['nombre'] }}</p>
        <p><strong>Descripción:</strong> {{ $item['descripcion'] }}</p>
        <p><strong>Unidad:</strong> {{ $item['unidad'] }}</p>
        <hr>
    @endforeach
</body>

</html>
