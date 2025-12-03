<?php
session_start();
if (!isset($_SESSION['tip_usu']) || $_SESSION['tip_usu'] != 1) { header('Location: ../login.php'); exit; }
require_once __DIR__ . '/../../lib/gestor_citas.php';
require_once __DIR__ . '/../../lib/gestor_recetas.php'; // Para reutilizar obtener_clientes
$clientes = obtener_clientes();
$citas = obtener_citas_medico($_SESSION['id_usr']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Citas</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.css">
    <link rel="stylesheet" href="../assets/css/bootstrap-icons.css">
</head>
<?php require_once __DIR__. '/menu-1.php' ?>
<body class="d-flex">
    <?php require_once __DIR__. '/menu-1.php' ?>
    
    <div class="container-fluid p-4">
        <h2>Agenda Médica</h2>
        
        <div class="card p-3 mb-4 bg-light">
            <h5>Agendar Nueva Cita</h5>
            <form action="../../lib/gestor_citas.php" method="POST" class="row g-3">
                <input type="hidden" name="accion_cita" value="agendar">
                <div class="col-md-3">
                    <label>Cliente</label>
                    <select name="id_cliente" class="form-select" required>
                        <?php foreach($clientes as $c): ?>
                            <option value="<?= $c['id_usr'] ?>"><?= $c['nom_usr'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Fecha</label>
                    <input type="date" name="fecha" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <label>Hora</label>
                    <input type="time" name="hora" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label>Motivo</label>
                    <input type="text" name="motivo" class="form-control" placeholder="Consulta general...">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Agendar Cita</button>
                </div>
            </form>
        </div>

        <table class="table border">
            <thead class="table-dark">
                <tr><th>Fecha</th><th>Hora</th><th>Cliente</th><th>Motivo</th><th>Estatus</th></tr>
            </thead>
            <tbody>
                <?php foreach($citas as $cita): ?>
                <tr>
                    <td><?= $cita['fecha_cita'] ?></td>
                    <td><?= $cita['hora_cita'] ?></td>
                    <td><?= $cita['cliente'] ?></td>
                    <td><?= $cita['motivo'] ?></td>
                    <td><span class="badge bg-warning text-dark"><?= $cita['estatus'] ?></span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script src="../assets/js/bootstrap.min.js"></script>
</body>
</html> 