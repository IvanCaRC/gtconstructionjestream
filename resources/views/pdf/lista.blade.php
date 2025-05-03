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
    <p><strong>Generado por:</strong> {{ $usuario }} {{ $usuario_first_last_name }} {{ $usuario_second_last_name }}
    </p>
    <p><strong>Cliente:</strong> {{ $cliente_nombre }}</p>
    <p><strong>Correo:</strong> {{ $cliente_correo }}</p>
    <p><strong>Dirección:</strong> {{ $cliente_direccion }}</p>
    <p><strong>Contacto del Cliente:</strong> {{ $cliente_contacto }}</p>
    <p><strong>Teléfono:</strong> {{ $cliente_telefono }}</p>
    <p><strong>Ítems a Cotizar:</strong> {{ $items_cotizar }}</p>
    @foreach ($items_cotizar_data as $item)
        <p><strong>Ruta generada:</strong> {{ $item['imagen'] }}</p>
        <p><strong>Imagen del Ítem:</strong></p>
        <img src="{{ asset($item['imagen']) }}">
        <p><strong>Nombre:</strong> {{ $item['nombre'] }}</p>
        <p><strong>Marca:</strong> {{ $item['marca'] }}</p>
        <p><strong>Cantidad Solicitada:</strong> {{ $item['cantidad'] }}</p>
        <hr>
    @endforeach
    <p><strong>Ítems Temporales:</strong> {{ $items_cotizar_temporales }}</p>
    @foreach ($items_cotizar_temporales_data as $item)
        <p><strong>Nombre:</strong> {{ $item['nombre'] }}</p>
        <p><strong>Descripción:</strong> {{ $item['descripcion'] }}</p>
        <p><strong>Unidad:</strong> {{ $item['unidad'] }}</p>
        <hr>
    @endforeach
</body>

</html>
