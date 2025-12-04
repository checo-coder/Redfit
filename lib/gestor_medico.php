<?php
@session_start();

require_once __DIR__.'/../config/db.php';
function mostrar_productos(){
    global $conn;
    $sql="SELECT * FROM medico";
    $select_preparado= mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($select_preparado);

    // solo es un select
    $resultado=mysqli_stmt_get_result($select_preparado);
    $productos=array();

    while($fila_db=mysqli_fetch_assoc($resultado)){
        $productos[]=$fila_db;

    }
    mysqli_stmt_close($select_preparado);
    return $productos;
    
}

function agregar_producto($nom_usr, $mail, $pass, $dir_usr, $tip_usu, $numero, $cedula, $img, $estatus){
    global $conn;
    $sql="INSERT INTO medico (nom_usr, `mail`, pass, dir_usr, tip_usu, numero, cedula, img, estatus) VALUES (?,?,?,?,?,?,?,?,?)";
    $insert_preparado=mysqli_prepare($conn, $sql);
    
    if(!$insert_preparado){
        //retun false 
        return [
            'estatus'=> 'error',
            'mensaje'=> 'error en la ejecucion en la base de datos'
        ];

    }

    mysqli_stmt_bind_param($insert_preparado, 'ssssisssi',$nom_usr, $mail, $pass, $dir_usr, $tip_usu, $numero, $cedula, $img, $estatus);
    $query_ok=mysqli_stmt_execute($insert_preparado); //True o False 

    $rows_ok =mysqli_affected_rows($conn); //0>1
    mysqli_stmt_close($insert_preparado);
    if($query_ok && $rows_ok > 0){
        return [
            'estatus'=>'msg',
            'mensaje'=>'medico agregado correctamente'
        ];

    }else{
        return[
        'estatus'=>'error',
        'mensaje'=>'Error al insertar el medico. no hubo cambios'
        ];

    }

}

function eliminar_medico($id_usr){
    global $conn;
    $sql="DELETE FROM medico WHERE id_usr=?";
    $delete_preparado=mysqli_prepare($conn, $sql);


        if(!$delete_preparado){
        //retun false 
        return [
            'estatus'=> 'error',
            'mensaje'=> 'error en la ejecucion en la base de datos'
        ];

    }
    mysqli_stmt_bind_param($delete_preparado, 'i', $id_usr);
    try {
    $query_ok=mysqli_stmt_execute($delete_preparado); //True o False 

    $rows_ok =mysqli_affected_rows($conn); //0>1
    mysqli_stmt_close($delete_preparado);
    if($query_ok && $rows_ok > 0){
        return [
            'estatus'=>'msg',
            'mensaje'=>'medico borrado correctamente'
        ];

    }else{
        return[
        'estatus'=>'error',
        'mensaje'=>'Error al borrar el medico. no hubo cambios'
        ];

    }
    } catch (mysqli_sql_exception $e) {
        // Si ocurre un error, el código salta directamente aquí
        
        // El código 1451 en MySQL significa "Fallo de llave foránea"
        if ($e->getCode() == 1451) {
            return [
                'estatus'=>'error',
                'mensaje'=>'NO SE PUEDE BORRAR: Este médico tiene pacientes o citas asignadas. Reasígnalos primero.'
            ];
        } else {
            // Cualquier otro error raro
            return [
                'estatus'=>'error',
                'mensaje'=>'Error desconocido de base de datos: ' . $e->getMessage()
            ];
        }
    }

}

function modificar_medico($nom_usr, $numero, $img, $estatus, $direccion, $id_usr){
    global $conn;
    $sql="UPDATE medico SET nom_usr=?, numero=?, img=?, estatus=?, dir_usr=? WHERE id_usr=?";
    $insert_preparado=mysqli_prepare($conn, $sql);
    
    if(!$insert_preparado){
        //retun false 
        return [
            'estatus'=> 'error',
            'mensaje'=> 'error en la ejecucion en la base de datos'
        ];

    }

    mysqli_stmt_bind_param($insert_preparado, 'sssisi',$nom_usr, $numero, $img, $estatus, $direccion, $id_usr);
    $query_ok=mysqli_stmt_execute($insert_preparado); //True o False 

    $rows_ok =mysqli_affected_rows($conn); //0>1
    mysqli_stmt_close($insert_preparado);
    if($query_ok && $rows_ok > 0){
        return [
            'estatus'=>'msg',
            'mensaje'=>'medico modificado correctamente'
        ];

    }else{
        return[
        'estatus'=>'error',
        'mensaje'=>'Error al insertar el medico. no hubo cambios'
        ];

    }

}

    if($_SERVER['REQUEST_METHOD']==='POST'){
        if(isset($_POST['accion'])){
            $accion=$_POST['accion'];

            switch($accion){
                case 'agregar':
                    if(isset($_POST['nom_med'],$_POST['mail'],$_POST['pass'],$_POST['img'],$_POST['num'],$_POST['cedula'],$_POST['dir'])){
                        $nom_usr=trim($_POST['nom_med']);
                        $mail=trim($_POST['mail']);
                        $pass=trim($_POST['pass']);
                        $dir_usr=trim($_POST['dir']);
                        $tip_usu=2;
                        $numero=trim($_POST['num']);
                        $cedula=trim($_POST['cedula']);
                        $img=trim($_POST['img']);
                        $estatus=1;


                        $resultado=agregar_producto($nom_usr, $mail, $pass, $dir_usr, $tip_usu, $numero, $cedula, $img, $estatus);
                        header('Location: ../public/admin/alta_medicos.php?msg=' .urlencode($resultado['mensaje']));
                        exit;


                    }else{
                        header('Location: ../public/admin/alta_medicos.php?error=' .urlencode('Hubo un error en el formulario, favor de revisar los campos'));
                        exit;
                    }
                    break;
                case 'editar':
                    if(isset($_POST['id_usr'], $_POST['nom_usr'], $_POST['numero'], $_POST['img'], $_POST['estatus'], $_POST['dir'])){
                        $id_usr=(int)$_POST['id_usr'];
                        $nom_usr=trim($_POST['nom_usr']);
                        $numero=trim($_POST['numero']);
                        $img=trim($_POST['img']);
                        $estatus=(int)$_POST['estatus'];
                        $direccion=trim($_POST['dir']);
                        $resultado=modificar_medico($nom_usr, $numero, $img, $estatus, $direccion, $id_usr);
                        echo"
                    <script>
                    alert('".$resultado['mensaje']."');
                    window.location.href='../public/admin/medicos.php';
                    </script>
                    ";  

                    }else{
                        echo"
                    <script>
                    alert('Algun dato  no proporcionado, intente de nuevo ".$id_prod['id_prod'], $nom_prod['nom_prod'], $desc['desc'], $prec['prec'], $stock['stock'], $estatus['stock']."');
                    window.location.href='../public/admin/editar_medico.php';
                    </script>
                    ";
                        
                    //    header('Location: ../public/admin/editar_productos.php?error=' .urlencode('Algun dato  no proporcionado, intente de nuevo'));
                    }
                    break;
                case 'eliminar':
                    if(isset($_POST['id_usr'])){
                        $id_usr=(int)$_POST['id_usr'];
                        $resultado=eliminar_medico($id_usr);
                    echo"
                    <script>
                    alert('".$resultado['mensaje']."');
                    window.location.href='../public/admin/medicos.php';
                    </script>
                    ";   
                        

                    }
                    else{
                        header('Location: ../public/admin/catalogo.php?error=' .urlencode('ID del medico no proporcionado, intente de nuevo'));
                    }
                        exit;
                    break;
                default:

            }

        }else{
            echo"
            <script>
            alert('Accion no detectada intente de nuevo');
            window.location.href='../public/admin/medicos.php';
            </script>
            ";
            exit;
        }

    }


?>