<?php
// citas/listado.php
require_once '../includes/db.php';
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

$sql = "SELECT c.id, c.fecha, c.hora, c.motivo, c.estado, 
               p.nombre AS paciente, u.nombre AS doctor
        FROM citas c
        JOIN pacientes p ON c.id_paciente = p.id
        JOIN usuarios u ON c.id_usuario = u.id
        ORDER BY c.fecha, c.hora";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agenda de Citas</title>
    <link rel="stylesheet" href="../assets/estilos.css">
</head>
<body>
    <h1>Agenda de Citas</h1>
    <a href="agendar.php">Agendar nueva cita</a>
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Paciente</th>
                <th>Doctor</th>
                <th>Motivo</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['fecha']) ?></td>
                <td><?= htmlspecialchars($row['hora']) ?></td>
                <td><?= htmlspecialchars($row['paciente']) ?></td>
                <td><?= htmlspecialchars($row['doctor']) ?></td>
                <td><?= htmlspecialchars($row['motivo']) ?></td>
                <td><?= htmlspecialchars($row['estado']) ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <a href="../index.php">Volver al panel</a>
</body>
</html>
