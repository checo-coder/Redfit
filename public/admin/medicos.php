<?php
session_start();
if ($_SESSION['tip_usu']==2||!isset($_SESSION['tip_usu'])){
header('Location: ../login.php?error='.urlencode('Acceso no autorizado'));
exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Admin</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.css">
    <link rel="stylesheet" href="../assets/css/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/login.css">
</head>

<body>
    <!-- CONTENIDO -->
     <?php require_once __DIR__. '/nav.php' ?>
    <main class="d-flex">
        <div id="admin-main" class="p-4">
            <h1>Listado de medicos</h1>
            <a href=./alta_medicos.php class="btn btn-primary"><i class="bi bi-arrow-return-left me-2 fs-5"></i></a>
            <table class="table table-sriped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre medico</th>
                    <th>E-mail</th>
                    <th>Direccion</th>
                    <th class="w-25">Imagen</th>
                    <th>Estatus</th>
                    <th>numero</th>
                    <th>cedula</th>
                    <th>Acciones</th>

                </tr>
                </thead>
                <tbody>
                    <?php 
                    require_once __DIR__ .'/../../lib/gestor_medico.php';
                    $medicos=mostrar_productos();
                    foreach($medicos as $fila_tabla){
                        if($fila_tabla['tip_usu']==2){
                            
                        
                    
                    ?>
                    <tr>
                    <td><?= $fila_tabla['id_usr'] ?></td>
                    <td><?= $fila_tabla['nom_usr'] ?></td>
                    <td><?= $fila_tabla['mail'] ?></td>
                    <td><?= $fila_tabla['dir_usr'] ?></td>
                    <td><img class="img-fluid" src=" <?= $fila_tabla['img'] ?>" alt=""></td>
                    <td><?=$mensaje=$fila_tabla['estatus']?"Activo":"Inactivo"; ?></td>
                    <td><?= $fila_tabla['numero'] ?></td>
                    <td><?= $fila_tabla['cedula'] ?></td>
                    <td> <a href="editar_medico.php?id_usr=<?= $fila_tabla['id_usr']?>" class="btn btn-warning mb-3"> Editar</a>
                    <form action="../../lib/gestor_medico.php" method="post">
                        <input type="hidden" name="id_usr" value="<?= $fila_tabla['id_usr'] ?>">
                        <button class="btn btn-danger" type="submit" name="accion" value="eliminar">Eliminar</button>

                    </form>
                        </tr>
                    
                    <?php 
                        }
                    }
                    
                    ?>
                </tbody>
            </table>
        </div>

    </main>
</body>

</html>