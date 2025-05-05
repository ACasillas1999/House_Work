<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}
$titulo = 'Sistema de Clínica';
include 'includes/header.php';
?>

<div class="panel-container">
    <h1>Bienvenido, <?= htmlspecialchars($_SESSION['nombre']) ?> 👋</h1>
    <div class="panel-grid">
        <div class="panel-box"><a href="pacientes/listado.php">📋 Pacientes</a></div>
        <div class="panel-box"><a href="citas/listado.php">📅 Citas</a></div>
        <div class="panel-box"><a href="citas/calendario.php">🗓️ Calendario</a></div>
        <div class="panel-box"><a href="historial/listado.php">📖 Historial Clínico</a></div>
        <?php if ($_SESSION['rol'] === 'admin'): ?>
        <div class="panel-box"><a href="usuarios/registro.php">👤 Registrar Usuario</a></div>
        <?php endif; ?>
        <?php if ($_SESSION['rol'] === 'doctor'): ?>
        <div class="panel-box"><a href="panel_doctor.php">🩺 Mi Panel Médico</a></div>
        <?php endif; ?>
    </div>
    <p style="text-align: center; margin-top: 40px;"><a href="usuarios/logout.php">🔓 Cerrar sesión</a></p>
</div>

<?php include 'includes/footer.php'; ?>
