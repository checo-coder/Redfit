<?php
session_start();
// 1. SEGURIDAD: Verificar sesión (Igual que en tu index.php)
if ($_SESSION['tip_usu'] == 1 || !isset($_SESSION['tip_usu']) || $_SESSION['estatus'] == 0 || !isset($_SESSION['estatus'])) {
    header('Location: ../login.php?error=' . urlencode('Acceso no autorizado'));
    exit();
}

// 2. CONEXIÓN A BD
require_once '../../config/db.php'; // Ajusta la ruta si db.php está en otro lado
$conexion = new mysqli("localhost", "root", "", "redfit"); // Asegúrate de usar tus credenciales reales si db.php no devuelve $conexion

$id_medico = $_SESSION['id_usr']; // El ID del médico logueado
$id_cliente_seleccionado = isset($_GET['cliente']) ? $_GET['cliente'] : null;

// 3. PROCESAR ENVÍO DE MENSAJE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mensaje']) && $id_cliente_seleccionado) {
    $mensaje = $_POST['mensaje'];
    $stmt = $conexion->prepare("INSERT INTO mensajes (id_remitente, id_destinatario, contenido, es_medico, fecha) VALUES (?, ?, ?, 1, NOW())");
    $stmt->bind_param("iis", $id_medico, $id_cliente_seleccionado, $mensaje);
    $stmt->execute();
    header("Location: chat_medico.php?cliente=" . $id_cliente_seleccionado); // Recargar
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Chat Médico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css"> <style>
        .chat-box { height: 400px; overflow-y: scroll; background: #f8f9fa; padding: 20px; border-radius: 10px; }
        .msg-cliente { text-align: left; margin-bottom: 10px; }
        .msg-medico { text-align: right; margin-bottom: 10px; }
        .burbuja { display: inline-block; padding: 10px 15px; border-radius: 20px; max-width: 70%; }
        .b-cliente { background: #e9ecef; color: black; }
        .b-medico { background: #d32f2f; color: white; } /* Rojo RedFit */
    </style>
</head>
<body class="body">
    <?php require_once __DIR__ . '/menu-1.php' ?>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-black text-white">Pacientes</div>
                    <div class="list-group list-group-flush">
                        <?php
                        // Buscar clientes asignados a este médico (o todos los clientes)
                        $sql = "SELECT id_cli, nom_usr FROM clientes WHERE id_usr = $id_medico"; 
                        $result = $conexion->query($sql);
                        
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                $active = ($id_cliente_seleccionado == $row['id_cli']) ? 'active bg-danger border-danger' : '';
                                echo '<a href="?cliente='.$row['id_cli'].'" class="list-group-item list-group-item-action '.$active.'">';
                                echo '<i class="bi bi-person-circle"></i> ' . $row['nom_usr'];
                                echo '</a>';
                            }
                        } else {
                            echo '<div class="p-3">No tienes pacientes asignados.</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header">
                        Conversación
                        <?php if(!$id_cliente_seleccionado) echo "(Selecciona un paciente)"; ?>
                    </div>
                    <div class="card-body">
                        <?php if ($id_cliente_seleccionado): ?>
                            
                            <div class="chat-box mb-3" id="cajaChat">
                                <?php
                                // Consultar mensajes entre médico y cliente
                                $sql_msgs = "SELECT * FROM mensajes 
                                             WHERE (id_remitente = $id_medico AND id_destinatario = $id_cliente_seleccionado) 
                                                OR (id_remitente = $id_cliente_seleccionado AND id_destinatario = $id_medico) 
                                             ORDER BY id_mensaje ASC";
                                $msgs = $conexion->query($sql_msgs);

                                while($msg = $msgs->fetch_assoc()) {
                                    $esMio = ($msg['es_medico'] == 1);
                                    echo '<div class="' . ($esMio ? 'msg-medico' : 'msg-cliente') . '">';
                                    echo '<div class="burbuja ' . ($esMio ? 'b-medico' : 'b-cliente') . '">';
                                    echo htmlspecialchars($msg['contenido']);
                                    echo '</div></div>';
                                }
                                ?>
                            </div>

                            <form method="POST">
                                <div class="input-group">
                                    <input type="text" name="mensaje" class="form-control" placeholder="Escribe un mensaje..." required autocomplete="off">
                                    <button class="btn btn-danger" type="submit"><i class="bi bi-send"></i> Enviar</button>
                                </div>
                            </form>

                        <?php else: ?>
                            <div class="text-center py-5 text-muted">
                                <i class="bi bi-chat-left-text display-1"></i>
                                <p class="mt-3">Selecciona un paciente de la lista para ver el chat.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var chatBox = document.getElementById("cajaChat");
        if(chatBox) chatBox.scrollTop = chatBox.scrollHeight;
    </script>
</body>
</html>