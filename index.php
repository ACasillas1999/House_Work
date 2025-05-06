<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}
$titulo = 'Sistema de Clínica';
include 'includes/header.php';
?>

<style>
    /* SIDEBAR REDISEÑADA */
.sidebar {
    background-color: #1f3a93;
    width: 240px;
    height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 20px 0;
    position: fixed;
    top: 0;
    left: 0;
    overflow-y: auto;
    box-shadow: 2px 0 5px rgba(0,0,0,0.1);
}

.sidebar .sidebar-header {
    text-align: center;
    margin-bottom: 30px;
}

.sidebar .sidebar-header h2 {
    font-size: 18px;
    color: #fff;
    margin-bottom: 0;
    letter-spacing: 0.5px;
}

.sidebar .nav-links {
    display: flex;
    flex-direction: column;
    gap: 5px;
    padding: 0 20px;
}

.sidebar a {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 15px;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    transition: background 0.2s, transform 0.1s;
    font-size: 15px;
    white-space: nowrap;
}

.sidebar a:hover {
    background-color: #34495e;
    transform: translateX(2px);
}

.sidebar a i {
    font-size: 18px;
}

.sidebar .logout {
    margin-top: auto;
    text-align: center;
    padding: 15px 20px;
    color: #ecf0f1;
    font-size: 14px;
}

.sidebar .logout:hover {
    text-decoration: underline;
    color: #ffffff;
}

/* Alineación general del layout */
.main-layout {
    display: flex;
}

/* Panel principal con margen izquierdo por el ancho de la sidebar */
.main-panel {
    margin-left: 240px;
    padding: 40px;
    width: 100%;
    min-height: 100vh;
    background-color: #ecf0f1;
    box-sizing: border-box;
}

/* Top bar con bienvenida y logo */
.top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 40px;
}

.top-bar h1 {
    font-size: 26px;
    color: #1f3a93;
    margin: 0;
}

.logo {
    width: 60px;
    height: auto;
}

/* Tarjetas del dashboard */
.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 25px;
}

.dashboard-card {
    background-color: #ffffff;
    border-radius: 12px;
    padding: 30px;
    text-align: center;
    font-size: 17px;
    font-weight: 500;
    color: #1f3a93;
    box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.dashboard-card a {
    color: inherit;
    text-decoration: none;
    display: block;
}

.dashboard-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 20px rgba(0,0,0,0.1);
    background-color: #f9f9f9;
}

/* Responsive para móvil */
@media (max-width: 768px) {
    .main-panel {
        margin-left: 0;
        padding: 20px;
    }

    .top-bar {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }

    .logo {
        align-self: flex-end;
    }
}
.main-panel {
    margin-left: 240px;
    padding: 40px;
    padding-bottom: 80px; /* espacio extra para que no tape el footer */
    width: 100%;
    min-height: calc(100vh - 100px);
    background-color: #ecf0f1;
    box-sizing: border-box;
}



</style>

<div class="main-layout">
    <!-- Sidebar -->
    <div class="sidebar">
    <div>
        <div class="sidebar-header">
            <h2>CLÍNICA</h2>
        </div>
        <div class="nav-links">
            <a href="pacientes/listado.php"><i>📋</i>Pacientes</a>
            <a href="citas/listado.php"><i>📅</i>Citas</a>
            <a href="citas/calendario.php"><i>🗓️</i>Calendario</a>
            <a href="historial/listado.php"><i>📖</i>Historial</a>
            <?php if ($_SESSION['rol'] === 'admin'): ?>
                <a href="usuarios/registro.php"><i>👤</i>Registrar Usuario</a>
            <?php endif; ?>
            <?php if ($_SESSION['rol'] === 'doctor'): ?>
                <a href="panel_doctor.php"><i>🩺</i>Mi Panel Médico</a>
            <?php endif; ?>
        </div>
    </div>
    <div class="logout">
        <a href="usuarios/logout.php">🔓 Cerrar sesión</a>
    </div>
</div>


    <!-- Panel principal -->
    <div class="main-panel">
        <div class="top-bar">
            <h1>Bienvenido, <?= htmlspecialchars($_SESSION['nombre']) ?> 👋</h1>
            <img src="assets/logo-clinica.png" alt="Logo Clínica" class="logo">
        </div>

        <div class="dashboard-grid">
            <div class="dashboard-card"><a href="pacientes/listado.php">📋 Ver Pacientes</a></div>
            <div class="dashboard-card"><a href="citas/listado.php">📅 Ver Citas</a></div>
            <div class="dashboard-card"><a href="citas/calendario.php">🗓️ Calendario</a></div>
            <div class="dashboard-card"><a href="historial/listado.php">📖 Historial Médico</a></div>
            <?php if ($_SESSION['rol'] === 'admin'): ?>
                <div class="dashboard-card"><a href="usuarios/registro.php">👤 Nuevo Usuario</a></div>
            <?php endif; ?>
            <?php if ($_SESSION['rol'] === 'doctor'): ?>
                <div class="dashboard-card"><a href="panel_doctor.php">🩺 Mi Panel</a></div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
