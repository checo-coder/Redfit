<?php
session_start();
if ($_SESSION['tip_usu']==2||!isset($_SESSION['tip_usu'])){
    header('Location: ../login.php?error='.urlencode('Acceso no autorizado'));
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Médico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="body">
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
        <nav class="navbar navbar-expand-lg navbar-dark bg-black">
        <div class="container-fluid px-4">
            <a class="navbar-brand d-flex align-items-center align-self-center" href="#">
                <img src="../assets/img/miniredfit.png" alt="Logo" class="icon-nav" style="height:60px;">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuLateral"
                aria-controls="menuLateral">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="offcanvas offcanvas-end" tabindex="-1" id="menuLateral">
                <div class="offcanvas-header bg-black">
                    <h5 class="offcanvas-title text-white">Menú</h5>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body bg-black">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        
                    <li class="nav-item m-3">
                        <a class="nav-link fw-semibold d-flex align-items-center" href="#">
                        <i class="bi bi-heart-pulse-fill me-2 fs-5"></i> Gestionar médicos
                        </a>
                    </li>

                    <li class="nav-item m-3">
                        <a class="nav-link fw-semibold d-flex align-items-center" href="#">
                        <i class="bi bi-lightbulb-fill me-2 fs-5"></i> Visualizar sugerencia
                        </a>
                    </li>

                    <li class="nav-item m-3">
                        <a class="nav-link fw-semibold d-flex align-items-center" href="#">
                        <i class="bi bi-bullseye me-2 fs-5"></i> Gestionar objetivos
                        </a>
                    </li>

                    <li class="nav-item m-3">
                        <a class="nav-link fw-semibold d-flex align-items-center" href="recetas_gestion.php">
                        <i class="bi bi-journal-text me-2 fs-5"></i> Mis Recetas
                        </a>
                    </li>
                    <li class="nav-item m-3">
                        <a class="nav-link fw-semibold d-flex align-items-center" href="recetas_asignar.php">
                        <i class="bi bi-calendar-week me-2 fs-5"></i> Asignar Dietas
                        </a>
                    </li>
                    <li class="nav-item m-3">
                        <a class="nav-link fw-semibold d-flex align-items-center" href="citas.php">
                        <i class="bi bi-calendar-event me-2 fs-5"></i> Citas Médicas
                        </a>
                    </li>
        
    </ul>
</div>
            </div>

        </div>
    </nav>
    <section>
        <img src="../assets/img/Redfit.png" class="logo mx-auto d-block mt-3" alt="Redfit Logo" style="max-height: 100px;">
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

            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm text-center p-4">
                    <div class="card-body">
                        <i class="bi bi-box-seam text-secondary display-4 mb-3"></i>
                        <h5 class="card-title">Catálogo Productos</h5>
                        <p class="card-text">Gestiona los productos de la tienda.</p>
                        <a href="catalogo.php" class="btn btn-secondary w-100">Ir al Catálogo</a>
                    </div>
                </div>
            </div>

        </div>
    </section>
    
</body> 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</html>