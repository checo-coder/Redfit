<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

// 1. SEGURIDAD: Solo médicos
if (!isset($_SESSION['tip_usu']) || $_SESSION['tip_usu'] != 2) {
    header('Location: ../login.php');
    exit();
}

$id_medico = $_SESSION['id_usr'];
$error = '';
$paciente = null;

// 2. LÓGICA POST (GUARDAR CAMBIOS)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_cli = intval($_POST['id_cli']);
    $nom_usr = trim($_POST['nom_usr']);
    $mail = trim($_POST['mail']);
    $dir_usr = trim($_POST['dir_usr']);
    $edad = intval($_POST['edad']);
    $sexo = $_POST['sexo'];
    $peso = floatval($_POST['peso']);
    $altura = floatval($_POST['altura']);
    $img = trim($_POST['img']);
    $id_obj = intval($_POST['id_obj']);

    // Validamos que el paciente pertenezca al médico antes de actualizar
    $sql_update = "UPDATE clientes SET nom_usr=?, mail=?, dir_usr=?, edad=?, sexo=?, peso=?, altura=?, img=?, id_obj=? 
                   WHERE id_cli=? AND id_usr=?";
    
    $stmt = mysqli_prepare($conn, $sql_update);
    mysqli_stmt_bind_param($stmt, 'sssisddsiii', $nom_usr, $mail, $dir_usr, $edad, $sexo, $peso, $altura, $img, $id_obj, $id_cli, $id_medico);
    
    if (mysqli_stmt_execute($stmt) && mysqli_affected_rows($conn) >= 0) { // >=0 por si guarda sin cambios
        header("Location: pacientes.php?msg=" . urlencode("Paciente actualizado correctamente"));
        exit();
    } else {
        $error = "Error al actualizar. Verifique los datos.";
    }
    mysqli_stmt_close($stmt);
}

// 3. LÓGICA GET (OBTENER DATOS PARA FORMULARIO)
if (isset($_GET['id'])) {
    $id_cli = intval($_GET['id']);
    
    // Consulta segura: WHERE id_cli = X AND id_usr = MEDICO_SESION
    $sql = "SELECT * FROM clientes WHERE id_cli = ? AND id_usr = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $id_cli, $id_medico);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $paciente = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);

    if (!$paciente) {
        header("Location: pacientes.php?msg=" . urlencode("Paciente no encontrado o no asignado."));
        exit();
    }
} else {
    header("Location: pacientes.php");
    exit();
}

// 4. OBTENER OBJETIVOS (Para el select)
$objetivos = [];
$res_obj = mysqli_query($conn, "SELECT * FROM objetivos WHERE estatus = 1");
if ($res_obj) {
    $objetivos = mysqli_fetch_all($res_obj, MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Paciente | Redfit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-light">

    <?php require_once __DIR__. '/menu-1.php' ?>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold">Editar Paciente</h2>
                    <a href="pacientes.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <form action="editar_paciente.php" method="POST" class="needs-validation" novalidate>
                            <input type="hidden" name="id_cli" value="<?= $paciente['id_cli'] ?>">

                            <h5 class="text-primary mb-3">Información Personal</h5>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Nombre Completo</label>
                                    <input type="text" name="nom_usr" class="form-control" value="<?= htmlspecialchars($paciente['nom_usr']) ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="mail" class="form-control" value="<?= htmlspecialchars($paciente['mail']) ?>" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Dirección</label>
                                    <input type="text" name="dir_usr" class="form-control" value="<?= htmlspecialchars($paciente['dir_usr']) ?>" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">URL Avatar (Imagen)</label>
                                    <input type="url" name="img" class="form-control" value="<?= htmlspecialchars($paciente['img']) ?>">
                                </div>
                            </div>

                            <h5 class="text-primary mb-3">Datos Físicos y Objetivos</h5>
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">Edad</label>
                                    <input type="number" name="edad" class="form-control" value="<?= $paciente['edad'] ?>" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Sexo</label>
                                    <select name="sexo" class="form-select">
                                        <option value="Masculino" <?= $paciente['sexo'] == 'Masculino' ? 'selected' : '' ?>>Masculino</option>
                                        <option value="Femenino" <?= $paciente['sexo'] == 'Femenino' ? 'selected' : '' ?>>Femenino</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Peso (kg)</label>
                                    <input type="number" step="0.01" name="peso" class="form-control" value="<?= $paciente['peso'] ?>" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Altura (m)</label>
                                    <input type="number" step="0.01" name="altura" class="form-control" value="<?= $paciente['altura'] ?>" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Objetivo Asignado</label>
                                    <select name="id_obj" class="form-select" required>
                                        <?php foreach ($objetivos as $obj): ?>
                                            <option value="<?= $obj['id_obj'] ?>" <?= $paciente['id_obj'] == $obj['id_obj'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($obj['nom_obj']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="mt-4 text-end">
                                <button type="submit" class="btn btn-primary px-4">Guardar Cambios</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>