<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        /* Aquí puedes incluir estilos básicos; recuerda que dompdf no soporta todo CSS */
        body {
            font-family: DejaVu Sans, sans-serif;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        /* Otros estilos necesarios */
    </style>
</head>
<body>
    <h1>{{ $title }}</h1>
    <p>Se mustran los item preliminares a buscar.</p>
    <p>{!! nl2br(e($proyecto->items_cotizar)) !!}</p>
    <!-- Agrega aquí el contenido que necesites -->
</body>
</html>
