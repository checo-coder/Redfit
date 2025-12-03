<nav class="navbar navbar-expand-lg navbar-dark bg-black">
    <div class="container-fluid px-4">
            <a class="navbar-brand d-flex align-items-center align-self-center" href="index.php">
                <img src="../assets/img/miniredfit.png" alt="Logo" class="icon-nav" style="height:60px;">
            </a>
             <!-- Botón hamburguesa -->
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuLateral"
                aria-controls="menuLateral">
                <span class="navbar-toggler-icon"></span>
            </button>

<div class="offcanvas bg-black offcanvas-end" tabindex="-1" id="menuLateral">
    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        
        <li class="nav-item m-3">
            <a class="nav-link fw-semibold d-flex align-items-center" href="#">
                <i class="bi bi-chat-text-fill me-2 fs-5"></i> Chats
            </a>
        </li>

        <li class="nav-item m-3">
            <a class="nav-link fw-semibold d-flex align-items-center" href="pacientes.php">
                <i class="bi bi-people-fill me-2 fs-5"></i> Gestionar clientes
            </a>
        </li>

        <li class="nav-item m-3">
            <a class="nav-link fw-semibold d-flex align-items-center" href="#">
                <i class="bi bi-calendar-check-fill me-2 fs-5"></i> Gestionar citas
            </a>
        </li>

        <li class="nav-item m-3">
            <a class="nav-link fw-semibold d-flex align-items-center" href="#">
                <i class="bi bi-clipboard2-pulse-fill me-2 fs-5"></i> Gestionar recetas
            </a>
        </li>

    </ul>
</div>
</div>
    </nav>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>