<?php
// panel_doctor.php
require_once 'includes/db.php';
session_start();

if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'doctor') {
    header("Location: login.php");
    exit;
}

$id_doctor = $_SESSION['id'];

$stmt = $conn->prepare("SELECT c.id, c.fecha, c.hora, c.motivo, c.estado,
                                p.nombre AS paciente
                         FROM citas c
                         JOIN pacientes p ON c.id_paciente = p.id
                         WHERE c.id_usuario = ?
                         ORDER BY c.fecha, c.hora");
$stmt->bind_param("i", $id_doctor);
$stmt->execute();
$citas = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel del Doctor</title>
    <link rel="stylesheet" href="assets/estilos.css">
</head>
<body>
    <h1>Bienvenido, Dr. <?= htmlspecialchars($_SESSION['nombre']) ?></h1>
    <h2>Mis Citas</h2>
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Paciente</th>
                <th>Motivo</th>
                <th>Estado</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($cita = $citas->fetch_assoc()): ?>
            <tr>
                <td><?= $cita['fecha'] ?></td>
                <td><?= $cita['hora'] ?></td>
                <td><?= htmlspecialchars($cita['paciente']) ?></td>
                <td><?= htmlspecialchars($cita['motivo']) ?></td>
                <td><?= $cita['estado'] ?></td>
                <td>
                    <a href="historial/registrar.php?id_paciente=<?= $cita['id'] ?>">Registrar Historial</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <a href="usuarios/logout.php">Cerrar sesión</a>
</body>
</html>