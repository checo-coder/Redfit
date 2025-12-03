<?php
require_once __DIR__.'/../config/db.php';

function agendar_cita($id_medico, $id_cliente, $fecha, $hora, $motivo){
    global $conn;
    $sql = "INSERT INTO citas (id_medico, id_cliente, fecha_cita, hora_cita, motivo) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'iisss', $id_medico, $id_cliente, $fecha, $hora, $motivo);
    $exito = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $exito;
}
 
function obtener_citas_medico($id_medico){
    global $conn;
    $sql = "SELECT c.*, m.nom_usr as cliente 
            FROM citas c 
            JOIN medico m ON c.id_cliente = m.id_usr 
            WHERE c.id_medico = ? ORDER BY c.fecha_cita, c.hora_cita";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id_medico);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion_cita'])){
    session_start();
    if($_POST['accion_cita'] === 'agendar'){
        $res = agendar_cita($_SESSION['id_usr'], $_POST['id_cliente'], $_POST['fecha'], $_POST['hora'], $_POST['motivo']);
        header("Location: ../public/admin/citas.php?msg=" . ($res ? "Cita agendada" : "Error"));
    }
}
?>