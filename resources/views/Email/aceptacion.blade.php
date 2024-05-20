<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Cambio de estatus de Idea</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            text-align: center;
            font-size: 1.3em;
        }

        .image-container {
            width: 50%;
            margin: auto;
            align-items: center;
        }

        .image-container img {
            width: 100%;
            height: auto;
            align-items: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Hola</h1>
        <p>Estimado(a) {{ e($user->nombre ?? 'usuario') }}, nos comunicamos para hacerle saber que su idea ha sido aceptada y próximamente implementada.</p>
        <p>Estamos muy contentos de poder contar con su idea y esperamos que esta sea de gran ayuda para la comunidad.</p>
        <p>Agradecemos su aporte y esperamos seguir contando con su ayuda.</p>
        <p>Atentamente, el equipo de Premia tu Idea.</p>
        <br />
        <p>Para visualizar los cambios, ingrese a nuestro sistema</p>
    </div>
    <div class="image-container">
        <img src="https://electrifynews.com/wp-content/uploads/2023/06/borgwarner-charges-ahead-with-a-bold-new-logo-ElectrifyNews.jpg" alt="Imagen de aceptación">
    </div>
</body>

</html>