<?php
require_once __DIR__.'/../config/db.php';

// --- GESTIÓN DE RECETAS ---

function crear_receta($nombre, $ingredientes, $desc, $preparacion, $calorias, $id_medico){
    global $conn;
    $sql = "INSERT INTO recetas (nombre, ingredientes, descripcion, preparacion, calorias, id_medico) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ssssii', $nombre, $ingredientes, $desc, $preparacion, $calorias, $id_medico);
    $exito = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $exito;
}
 
function modificar_receta($id_receta, $nombre, $ingredientes, $desc, $preparacion, $calorias){
    global $conn;
    $sql = "UPDATE recetas SET nombre=?, ingredientes=?, descripcion=?, preparacion=?, calorias=? WHERE id_receta=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ssssii', $nombre, $ingredientes, $desc, $preparacion, $calorias, $id_receta);
    $exito = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $exito;
}

function eliminar_receta($id_receta){
    global $conn;
    // Nota: Si hay claves foráneas en plan_semanal, esto podría fallar si no se borran primero las asignaciones.
    // Asumiremos borrado simple o cascada configurada en DB.
    $sql = "DELETE FROM recetas WHERE id_receta=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id_receta);
    $exito = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $exito;
}

function obtener_recetas(){
    global $conn;
    $sql = "SELECT * FROM recetas ORDER BY id_receta DESC";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function obtener_receta_por_id($id){
    global $conn;
    $sql = "SELECT * FROM recetas WHERE id_receta = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($res);
}

// --- GESTIÓN DEL PLAN SEMANAL ---

function asignar_plan($id_medico, $id_cliente, $id_receta, $dia, $comida){
    global $conn;
    $sql = "REPLACE INTO plan_semanal (id_medico, id_cliente, id_receta, dia_semana, tipo_comida) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'iiiss', $id_medico, $id_cliente, $id_receta, $dia, $comida);
    $exito = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $exito;
}

function obtener_planes_asignados($id_medico){
    global $conn;
    // Hacemos JOIN para traer el nombre del cliente y el nombre de la receta
    $sql = "SELECT p.*, c.nom_usr as cliente, r.nombre as receta_nombre, r.calorias 
            FROM plan_semanal p
            JOIN clientes c ON p.id_cliente = c.id_cli
            JOIN recetas r ON p.id_receta = r.id_receta
            WHERE p.id_medico = ?
            ORDER BY FIELD(p.dia_semana, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'),
                     FIELD(p.tipo_comida, 'Desayuno', 'Almuerzo', 'Comida', 'Cena')";
    
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id_medico);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

function obtener_clientes(){
    global $conn;
    $sql = "SELECT id_cli, nom_usr FROM clientes"; 
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}
function obtener_pacientes_por_medico($id_medico){
    global $conn;
    
    // Seleccionamos clientes que pertenezcan al médico (id_usr) y que estén activos (estatus=1)
    $sql = "SELECT * FROM clientes WHERE id_usr = ? AND estatus = 1 ORDER BY nom_usr ASC";
    
    $stmt = mysqli_prepare($conn, $sql);
    
    // 'i' indica que el id es un entero
    mysqli_stmt_bind_param($stmt, 'i', $id_medico);
    
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    
    $pacientes = [];
    while($row = mysqli_fetch_assoc($res)){
        $pacientes[] = $row;
    }
    return $pacientes;
}

// PROCESAMIENTO DE FORMULARIOS POST
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion_recetas'])){
    session_start();
    $accion = $_POST['accion_recetas'];
    $id_medico = $_SESSION['id_usr'];

    if($accion === 'crear'){
        $res = crear_receta($_POST['nombre'], $_POST['ingredientes'], $_POST['descripcion'], $_POST['preparacion'], $_POST['calorias'], $id_medico);
        $msg = $res ? "Receta creada correctamente" : "Error al crear receta";
        header("Location: ../public/cliente/recetas_gestion.php?msg=".urlencode($msg));
    }
    elseif($accion === 'editar'){
        $res = modificar_receta($_POST['id_receta'], $_POST['nombre'], $_POST['ingredientes'], $_POST['descripcion'], $_POST['preparacion'], $_POST['calorias']);
        $msg = $res ? "Receta modificada correctamente" : "Error al modificar";
        header("Location: ../public/cliente/recetas_gestion.php?msg=".urlencode($msg));
    }
    elseif($accion === 'eliminar'){
        $res = eliminar_receta($_POST['id_receta']);
        $msg = $res ? "Receta eliminada correctamente" : "Error al eliminar (posiblemente esté asignada a un plan)";
        header("Location: ../public/cliente/recetas_gestion.php?msg=".urlencode($msg));
    }
    elseif($accion === 'asignar'){
        $res = asignar_plan($id_medico, $_POST['id_cliente'], $_POST['id_receta'], $_POST['dia'], $_POST['comida']);
        $msg = $res ? "Plan asignado correctamente" : "Error al asignar";
        header("Location: ../public/cliente/recetas_asignar.php?msg=".urlencode($msg));
    }
}
?>