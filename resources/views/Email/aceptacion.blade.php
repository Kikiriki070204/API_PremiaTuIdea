<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Prueba exitosa</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h1>Hola :.:</h1>
        <p>Estimado(a) {{ e($user->nombre ?? 'usuario') }}</p>
    </div>
    <div class="container">
        <img src="/Images/logo.png" alt="Imagen de aceptación">
    </div>
</body>

</html>