<?php
session_start();
if ($_SESSION['tip_usu']==1||!isset($_SESSION['tip_usu'])||$_SESSION['estatus']==0||!isset($_SESSION['estatus'])){
header('Location: ../login.php?error='.urlencode('Acceso no autorizado'));
exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <!-- Fuente Montserrat -->
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="body">
    <?php require_once __DIR__. '/menu-1.php' ?>
    <style>
            @media (min-width: 760px) {
            #menuLateral .nav-link {
                color: #fff !important;
                margin-bottom: 0;
            }

            #menuLateral .nav-link:hover {
                color: #A3B2BF !important;
            }
           
        }
    </style>
    <section>
        <img src="assets/img/Redfit.png" class="logo" alt="">
    </section>
    <section class="container mb-5">
        <h1 class="mt-4 text-center">Bienvenido al panel de Médico</h1>
        <p class="text-center lead">Desde aquí puedes gestionar tus citas, ver tus recetas y comunicarte con tus clientes.</p>
        
        <div class="row mt-5">
            
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm text-center p-4">
                    <div class="card-body">
                        <i class="bi bi-journal-plus text-primary display-4 mb-3"></i>
                        <h5 class="card-title">Gestión de Recetas</h5>
                        <p class="card-text">Crea, edita y consulta el catálogo de recetas alimenticias disponibles.</p>
                        <a href="recetas_gestion.php" class="btn btn-primary w-100">Administrar Recetas</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm text-center p-4">
                    <div class="card-body">
                        <i class="bi bi-calendar-range text-success display-4 mb-3"></i>
                        <h5 class="card-title">Asignar Planes</h5>
                        <p class="card-text">Asigna dietas semanales personalizadas a tus clientes.</p>
                        <a href="recetas_asignar.php" class="btn btn-success w-100">Asignar Dieta</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm text-center p-4">
                    <div class="card-body">
                        <i class="bi bi-calendar-check text-warning display-4 mb-3"></i>
                        <h5 class="card-title">Agenda de Citas</h5>
                        <p class="card-text">Consulta tus citas programadas y agenda nuevas sesiones.</p>
                        <a href="citas.php" class="btn btn-warning text-dark w-100">Gestionar Citas</a>
                    </div>
                </div>
            </div>

        </div>
    </section>
    
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</html>