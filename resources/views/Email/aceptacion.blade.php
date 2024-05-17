<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Prueba exitosa</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .image-container {
            width: 50%;
            margin: auto;
        }

        .image-container img {
            width: 80%;
            height: auto;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Hola.</h1>
        <p>Estimado(a) {{ e($user->nombre ?? 'usuario') }}, nos comunicmos para hacerle saber que su idea ha sido aceptada y proximamente implementada.</p>
        <p>Gracias por su aporte.</p>
        <p>Saludos cordiales.</p>
        <p>Atentamente, el equipo de soporte.</p>
    </div>
    <div class="image-container">
        <img src="http:Images/logo.png" alt="Imagen de aceptación">
    </div>
</body>

</html>