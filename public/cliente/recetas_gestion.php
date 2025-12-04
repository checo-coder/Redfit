<?php
session_start();
if (!isset($_SESSION['tip_usu']) || $_SESSION['tip_usu'] != 2||$_SESSION['estatus']==0||!isset($_SESSION['estatus'])) { header('Location: ../login.php?error=' . urlencode('Acceso no autorizado')); exit; }
require_once __DIR__ . '/../../lib/gestor_recetas.php';

$mensaje = isset($_GET['msg']) ? $_GET['msg'] : '';
$edit_mode = false;
$datos_receta = [];

// Si se pulsa editar, cargamos los datos
if(isset($_GET['editar_id'])){
    $edit_mode = true;
    $datos_receta = obtener_receta_por_id($_GET['editar_id']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Recetas</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.css">
    <link rel="stylesheet" href="../assets/css/bootstrap-icons.css">
</head>
<body>
    <?php require_once __DIR__. '/menu-1.php' ?>
    
    <div class="container-fluid p-4">
        <h2><?= $edit_mode ? 'Editar Receta' : 'Administrar Recetas' ?></h2>
        
        <?php if($mensaje): ?>
            <div class="alert alert-info alert-dismissible fade show">
                <?= htmlspecialchars($mensaje) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card p-3 mb-4 shadow-sm">
            <h5 class="card-title text-primary"><?= $edit_mode ? 'Modificando: ' . htmlspecialchars($datos_receta['nombre']) : 'Nueva Receta' ?></h5>
            
            <form action="../../lib/gestor_recetas.php" method="POST">
                <input type="hidden" name="accion_recetas" value="<?= $edit_mode ? 'editar' : 'crear' ?>">
                <?php if($edit_mode): ?>
                    <input type="hidden" name="id_receta" value="<?= $datos_receta['id_receta'] ?>">
                <?php endif; ?>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label>Nombre del Platillo</label>
                        <input type="text" name="nombre" class="form-control" required value="<?= $edit_mode ? htmlspecialchars($datos_receta['nombre']) : '' ?>">
                    </div>
                    <div class="col-md-6">
                        <label>Calorías (kcal)</label>
                        <input type="number" name="calorias" class="form-control" required value="<?= $edit_mode ? htmlspecialchars($datos_receta['calorias']) : '' ?>">
                    </div>
                    <div class="col-12">
                        <label>Ingredientes</label>
                        <textarea name="ingredientes" class="form-control" rows="2" required><?= $edit_mode ? htmlspecialchars($datos_receta['ingredientes']) : '' ?></textarea>
                    </div>
                    <div class="col-12">
                        <label>Preparación</label>
                        <textarea name="preparacion" class="form-control" rows="3" required><?= $edit_mode ? htmlspecialchars($datos_receta['preparacion']) : '' ?></textarea>
                    </div>
                    <div class="col-12">
                        <label>Descripción Breve</label>
                        <input type="text" name="descripcion" class="form-control" value="<?= $edit_mode ? htmlspecialchars($datos_receta['descripcion']) : '' ?>">
                    </div>
                </div>
                
                <div class="mt-3">
                    <button type="submit" class="btn <?= $edit_mode ? 'btn-warning' : 'btn-success' ?>">
                        <?= $edit_mode ? 'Actualizar Receta' : 'Guardar Receta' ?>
                    </button>
                    
                    <?php if($edit_mode): ?>
                        <a href="recetas_gestion.php" class="btn btn-secondary">Cancelar Edición</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <h5 class="mt-5">Recetas Disponibles</h5>
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead class="table-dark"><tr><th>ID</th><th>Nombre</th><th>Calorías</th><th>Descripción</th><th>Acciones</th></tr></thead>
                <tbody>
                    <?php 
                    $recetas = obtener_recetas();
                    foreach($recetas as $r): ?>
                    <tr>
                        <td><?= $r['id_receta'] ?></td>
                        <td><?= htmlspecialchars($r['nombre']) ?></td>
                        <td><?= $r['calorias'] ?> kcal</td>
                        <td><?= htmlspecialchars($r['descripcion']) ?></td>
                        <td>
                            <a href="recetas_gestion.php?editar_id=<?= $r['id_receta'] ?>" class="btn btn-sm btn-primary">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            
                            <form action="../../lib/gestor_recetas.php" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que deseas eliminar esta receta?');">
                                <input type="hidden" name="accion_recetas" value="eliminar">
                                <input type="hidden" name="id_receta" value="<?= $r['id_receta'] ?>">
                                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="../assets/js/bootstrap.min.js"></script>
</body>
</html> 