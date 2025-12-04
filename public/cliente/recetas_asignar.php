<?php
session_start();
if (!isset($_SESSION['tip_usu']) || $_SESSION['tip_usu'] != 2||$_SESSION['estatus']==0||!isset($_SESSION['estatus'])) { header('Location: ../login.php?error=' . urlencode('Acceso no autorizado')); exit; }
require_once __DIR__ . '/../../lib/gestor_recetas.php';

$clientes = obtener_pacientes_por_medico($_SESSION['id_usr']);
$recetas = obtener_recetas();
$planes_asignados = obtener_planes_asignados($_SESSION['id_usr']); // Nueva función
$mensaje = isset($_GET['msg']) ? $_GET['msg'] : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asignar Plan Semanal</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.css">
    <link rel="stylesheet" href="../assets/css/bootstrap-icons.css">
</head>
<body>
    <?php require_once __DIR__. '/menu-1.php' ?>
    
    <div class="container-fluid p-4">
        <h2>Gestión de Dietas</h2>
        <?php if($mensaje): ?><div class="alert alert-info"><?= htmlspecialchars($mensaje) ?></div><?php endif; ?>

        <div class="row">
            <div class="col-md-4">
                <div class="card p-4 shadow-sm">
                    <h5 class="mb-3">Asignar Comida</h5>
                    <form action="../../lib/gestor_recetas.php" method="POST">
                        <input type="hidden" name="accion_recetas" value="asignar">
                        
                        <div class="mb-3">
                            <label class="form-label">Cliente</label>
                            <select name="id_cliente" class="form-select" required>
                                <option value="">-- Seleccione --</option>
                                <?php foreach($clientes as $c): ?>
                                    <option value="<?= $c['id_cli'] ?>"><?= $c['nom_usr'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label">Día</label>
                                <select name="dia" class="form-select" required>
                                    <option>Lunes</option><option>Martes</option><option>Miercoles</option>
                                    <option>Jueves</option><option>Viernes</option><option>Sabado</option><option>Domingo</option>
                                </select>
                            </div>
                            <div class="col">
                                <label class="form-label">Comida</label>
                                <select name="comida" class="form-select" required>
                                    <option>Desayuno</option><option>Almuerzo</option><option>Comida</option><option>Cena</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Receta</label>
                            <select name="id_receta" class="form-select" required>
                                <?php foreach($recetas as $r): ?>
                                    <option value="<?= $r['id_receta'] ?>"><?= $r['nombre'] ?> (<?= $r['calorias'] ?> kcal)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Asignar al Plan</button>
                    </form>
                </div>
            </div>

            <div class="col-md-8">
                <h5 class="mt-3 mt-md-0">Historial de Planes Activos</h5>
                <div class="table-responsive bg-white rounded shadow-sm p-2">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Cliente</th>
                                <th>Día</th>
                                <th>Momento</th>
                                <th>Receta</th>
                                <th>Calorías</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($planes_asignados)): ?>
                                <tr><td colspan="5" class="text-center text-muted">No hay planes asignados aún.</td></tr>
                            <?php else: ?>
                                <?php foreach($planes_asignados as $plan): ?>
                                <tr>
                                    <td class="fw-bold"><?= htmlspecialchars($plan['cliente']) ?></td>
                                    <td><span class="badge bg-secondary"><?= $plan['dia_semana'] ?></span></td>
                                    <td><?= $plan['tipo_comida'] ?></td>
                                    <td><?= htmlspecialchars($plan['receta_nombre']) ?></td>
                                    <td><?= $plan['calorias'] ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> 
    <script src="../assets/js/bootstrap.min.js"></script>
</body>
</html>