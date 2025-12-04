<?php
require_once __DIR__.'/../config/db.php'; 
// echo htmlspecialchars($_GET['email']);
if($_SERVER["REQUEST_METHOD"]==="POST"){
    require_once __DIR__.'/../config/db.php';
    if(isset($_POST['mail'],$_POST['pass'])){
        $mail=trim($_POST['mail']);
        $password=trim($_POST['pass']);

        echo "$mail, $password<br>";
        
        //consulta preparada
        $sql="SELECT id_usr, nom_usr, mail, pass, estatus ,tip_usu FROM medico WHERE mail = ?";
        $consulta_preparada= mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($consulta_preparada, "s", $mail);
        mysqli_stmt_execute($consulta_preparada);

        //obtener el resultado
        $resultado= mysqli_stmt_get_result($consulta_preparada);
        mysqli_stmt_close($consulta_preparada);
        //consulta

        if($resultado && mysqli_num_rows($resultado) == 1){
            $usuario= mysqli_fetch_assoc($resultado);
            //verificar la contraseña
            if(($usuario['pass']==$password)){
                session_start();
                $_SESSION['id_usr']=$usuario['id_usr'];
                $_SESSION['nom_usr']=$usuario['nom_usr'];
                $_SESSION['mail']=$usuario['mail'];
                $_SESSION['tip_usu']=$usuario['tip_usu'];
                $_SESSION['estatus']=$usuario['estatus'];
                echo $usuario['tip_usu'];

                if($usuario['tip_usu'] == 1){
                    header('Location: ../public/admin/index.php');
                    exit;
                }else{
                    header('Location: ../public/cliente/index.php');
                    exit;
                }
                
            }else{
                header('Location: ../public/login.php?error='.urlencode('Contraseña incorrecta'));
                exit;
            }


    }else{
        header('Location: ../public/login.php?error='.urlencode('Correo no encontrado'));
    exit;

    }

}else{
    header('Location: ../public/login.php?error='.urlencode('Acceso no permitido'));
    exit;

}
}
?>