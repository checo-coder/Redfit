<?php
session_start();

// 1. SEGURIDAD: Verificar que sea Médico (Tipo 2)
if (!isset($_SESSION['tip_usu']) || $_SESSION['tip_usu'] != 2||$_SESSION['estatus']==0||!isset($_SESSION['estatus'])) {
    header('Location: ../login.php?error=' . urlencode('Acceso no autorizado'));
    exit();
}

// 2. CONEXIÓN Y LÓGICA DE BORRADO
require_once __DIR__ . '/../../config/db.php'; 



// 3. CONSULTA DE PACIENTES
$id_medico = $_SESSION['id_usr'];
$busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';
$msg = isset($_GET['msg']) ? $_GET['msg'] : (isset($msg) ? $msg : '');

// Consulta Base
$sql = "SELECT c.*, o.nom_obj 
        FROM clientes c 
        LEFT JOIN objetivos o ON c.id_obj = o.id_obj 
        WHERE c.id_usr = ? AND c.estatus = 1";

// Agregamos filtro SQL si hay búsqueda
if (!empty($busqueda)) {
    $sql .= " AND (c.nom_usr LIKE ? OR c.mail LIKE ?)";
}

$sql .= " ORDER BY c.nom_usr ASC";

// Preparamos la consulta
$stmt = mysqli_prepare($conn, $sql);

if ($stmt === false) {
    die("Error SQL: " . mysqli_error($conn));
}

// --- CORRECCIÓN AQUÍ: Bind Param simple con IF/ELSE ---
if (!empty($busqueda)) {
    // Si hay búsqueda, pasamos 3 variables: ID (entero), Busqueda (string), Busqueda (string)
    $param_busqueda = "%" . $busqueda . "%";
    mysqli_stmt_bind_param($stmt, 'iss', $id_medico, $param_busqueda, $param_busqueda);
} else {
    // Si NO hay búsqueda, solo pasamos el ID del médico (entero)
    mysqli_stmt_bind_param($stmt, 'i', $id_medico);
}
// ------------------------------------------------------

mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Pacientes | Redfit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .avatar-img { width: 45px; height: 45px; object-fit: cover; border-radius: 50%; }
        .table-hover tbody tr:hover { background-color: #f8f9fa; }
    </style>
</head>
<body class="bg-light">

    <?php require_once __DIR__. '/menu-1.php' ?>

    <div class="container py-5">
        
        <div class="row mb-4 align-items-center">
            <div class="col-md-6">
                <h2 class="fw-bold text-dark"><i class="bi bi-people-fill text-primary"></i> Mis Pacientes</h2>
                <p class="text-muted">Gestiona la información personal de tus pacientes.</p>
            </div>
            <div class="col-md-6">
                <form action="pacientes.php" method="GET" class="d-flex gap-2">
                    <input type="text" name="busqueda" class="form-control" placeholder="Buscar nombre..." value="<?= htmlspecialchars($busqueda) ?>">
                    <button type="submit" class="btn btn-primary">Buscar</button>
                    <?php if(!empty($busqueda)): ?><a href="pacientes.php" class="btn btn-outline-danger"><i class="bi bi-x-lg"></i></a><?php endif; ?>
                </form>
            </div>
        </div>

        <?php if($msg): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($msg) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Paciente</th>
                                <th>Contacto</th>
                                <th>Físico</th>
                                <th>Objetivo</th>
                                <th class="text-end pe-4">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($resultado) > 0): ?>
                                <?php while($row = mysqli_fetch_assoc($resultado)): 
                                    $imgUrl = !empty($row['img']) ? $row['img'] : 'https://via.placeholder.com/45';
                                ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <img src="<?= htmlspecialchars($imgUrl) ?>" class="avatar-img me-3">
                                            <div>
                                                <h6 class="mb-0 fw-bold"><?= htmlspecialchars($row['nom_usr']) ?></h6>
                                                <small class="text-muted">ID: <?= $row['id_cli'] ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span><?= htmlspecialchars($row['mail']) ?></span>
                                            <small class="text-muted"><?= htmlspecialchars($row['dir_usr']) ?></small>
                                        </div>
                                    </td>
                                    <td><?= $row['peso'] ?> kg / <?= $row['altura'] ?> m</td>
                                    <td><span class="badge bg-info text-dark"><?= htmlspecialchars($row['nom_obj'] ?? 'N/A') ?></span></td>
                                    
                                    <td class="text-end pe-4">
                                        <a href="editar_paciente.php?id=<?= $row['id_cli'] ?>" class="btn btn-sm btn-primary" title="Editar Información">
                                            <i class="bi bi-pencil-square"></i> Editar
                                        </a>
                                        
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="5" class="text-center py-5">
                                    <?php if(!empty($busqueda)): ?>
                                        No se encontraron pacientes con ese nombre.
                                    <?php else: ?>
                                        No tienes pacientes asignados actualmente.
                                    <?php endif; ?>
                                </td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php mysqli_stmt_close($stmt); ?>