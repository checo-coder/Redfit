<?php
@session_start();

require_once __DIR__.'/../config/db.php';

function mostrar_objetivos(){
    global $conn;
    $sql="SELECT * FROM objetivos";
    $select_preparado= mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($select_preparado);
    $resultado=mysqli_stmt_get_result($select_preparado);
    $objetivos=array();
    while($fila_db=mysqli_fetch_assoc($resultado)){
        $objetivos[]=$fila_db;
    }
    mysqli_stmt_close($select_preparado);
    return $objetivos;
}

function agregar_objetivo($nom_obj, $desc_obj){
    global $conn;
    $estatus = 1; 
    $sql="INSERT INTO objetivos (nom_obj, desc_obj, estatus) VALUES (?,?,?)";
    $insert_preparado=mysqli_prepare($conn, $sql);
    
    if(!$insert_preparado){
        return ['estatus'=> 'error', 'mensaje'=> 'Error en la preparación de la BD'];
    }

    mysqli_stmt_bind_param($insert_preparado, 'ssi', $nom_obj, $desc_obj, $estatus);
    $query_ok=mysqli_stmt_execute($insert_preparado);

    $rows_ok =mysqli_affected_rows($conn);
    mysqli_stmt_close($insert_preparado);
    
    if($query_ok && $rows_ok > 0){
        return ['estatus'=>'msg', 'mensaje'=>'Objetivo agregado correctamente'];
    }else{
        return ['estatus'=>'error', 'mensaje'=>'Error al insertar. No hubo cambios'];
    }
}

function eliminar_objetivo($id_obj){
    global $conn;
    $sql="DELETE FROM objetivos WHERE id_obj=?";
    $delete_preparado=mysqli_prepare($conn, $sql);

    if(!$delete_preparado){
        return ['estatus'=> 'error', 'mensaje'=> 'Error en la preparación de la BD'];
    }
    mysqli_stmt_bind_param($delete_preparado, 'i', $id_obj);
    try {
    $query_ok=mysqli_stmt_execute($delete_preparado); 

    $rows_ok =mysqli_affected_rows($conn);
    mysqli_stmt_close($delete_preparado);
    
    if($query_ok && $rows_ok > 0){
        return ['estatus'=>'msg', 'mensaje'=>'Objetivo eliminado correctamente'];
    }else{
        return ['estatus'=>'error', 'mensaje'=>'Error al borrar. No hubo cambios'];
    }
} catch (Exception $e) {
        return ['estatus'=>'error', 'mensaje'=>'Error al eliminar. El objetivo esta en uso cominiquese con sus medicos para que lo reasignen.'];
    }
}

function modificar_objetivo($nom_obj, $desc_obj, $estatus, $id_obj){
    global $conn;
    $sql="UPDATE objetivos SET nom_obj=?, desc_obj=?, estatus=? WHERE id_obj=?";
    $update_preparado=mysqli_prepare($conn, $sql);
    
    if(!$update_preparado){
        return ['estatus'=> 'error', 'mensaje'=> 'Error en la preparación de la BD'];
    }

    mysqli_stmt_bind_param($update_preparado, 'ssii', $nom_obj, $desc_obj, $estatus, $id_obj);
    $query_ok=mysqli_stmt_execute($update_preparado);

    $rows_ok =mysqli_stmt_affected_rows($update_preparado);
    mysqli_stmt_close($update_preparado);
    
    // Validamos query_ok para errores de SQL, rows_ok > 0 para cambios reales, o 0 si los datos son iguales
    if($query_ok){
        return ['estatus'=>'msg', 'mensaje'=>'Objetivo modificado correctamente'];
    }else{
        return ['estatus'=>'error', 'mensaje'=>'Error al modificar.'];
    }
}

if($_SERVER['REQUEST_METHOD']==='POST'){
    if(isset($_POST['accion'])){
        $accion=$_POST['accion'];

        switch($accion){
            case 'agregar':
                if(isset($_POST['nom_obj'], $_POST['desc_obj'])){
                    $nom_obj=trim($_POST['nom_obj']);
                    $desc_obj=trim($_POST['desc_obj']);

                    $resultado=agregar_objetivo($nom_obj, $desc_obj);
                    header('Location: ../public/admin/alta_objetivos.php?msg=' .urlencode($resultado['mensaje']));
                    exit;
                }else{
                    header('Location: ../public/admin/alta_objetivos.php?error=' .urlencode('Error en formulario, revise campos'));
                    exit;
                }
                break;

            case 'editar':
                if(isset($_POST['id_obj'], $_POST['nom_obj'], $_POST['desc_obj'], $_POST['estatus'])){
                    $id_obj=(int)$_POST['id_obj'];
                    $nom_obj=trim($_POST['nom_obj']);
                    $desc_obj=trim($_POST['desc_obj']);
                    $estatus=(int)$_POST['estatus'];

                    $resultado=modificar_objetivo($nom_obj, $desc_obj, $estatus, $id_obj);
                    echo "<script>
                        alert('".$resultado['mensaje']."');
                        window.location.href='../public/admin/objetivos.php';
                    </script>";  
                }else{
                     echo "<script>
                        alert('Datos incompletos');
                        window.location.href='../public/admin/objetivos.php';
                    </script>";
                }
                break;

            case 'eliminar':
                if(isset($_POST['id_obj'])){
                    $id_obj=(int)$_POST['id_obj'];
                    $resultado=eliminar_objetivo($id_obj);
                    echo "<script>
                        alert('".$resultado['mensaje']."');
                        window.location.href='../public/admin/objetivos.php';
                    </script>";   
                } else{
                    header('Location: ../public/admin/objetivos.php?error=' .urlencode('ID no proporcionado'));
                }
                exit;
                break;
            default:
        }
    } else {
        echo "<script>alert('Accion no detectada'); window.location.href='../public/admin/objetivos.php';</script>";
        exit;
    }
}
?>