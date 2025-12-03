<?php
session_start();
$mensaje=isset($_GET['error']) ? $_GET['error'] : '';
// if(isset($_GET['erro'])){
//     echo $GET['erro'];
// }else{
//     echo "no hay error";
// }
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Login - Gestor Web</title>
    <link href="assets/css/login.css" rel="stylesheet">
    <link href="assets/css/bootstrap.css" rel="stylesheet">
</head>
<style>
    .body{
        background-color: #DCE8F0;
    }
</style>
<body class="body">
    <section>
        <img class="logo" src="assets/img/Redfit.png" alt="">
    </section>
    <section>
        <div class="login-container">
            <h2 class="title-register">Inicio de sesion</h2>
            <?php
                if ($mensaje){
                ?>
                    <div class="alert alert-danger" role="alert">
                    <?= $mensaje ?>
                  </div>
                <?php
                }
                ?>
            <form action="../lib/procesar_login.php" class="login-form" method="post">
                <input class="input" type="email" placeholder="Correo Electrónico" id="mail" name="mail" required>
                <input class="input" type="password" placeholder="Contraseña" id="pass" name="pass" required>
                <button class="btn-enviar" type="submit">Ingresar</button>
            </form>

        </div>
    </section>
    <script src="./assets/js/bootstrap.min.js"></script>
</body>

</html>