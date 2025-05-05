<?php
// historial/listado.php
require_once '../includes/db.php';
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

$sql = "SELECT h.id, h.fecha, h.descripcion, h.receta, h.archivo, p.nombre AS paciente
        FROM historiales h
        JOIN pacientes p ON h.id_paciente = p.id
        ORDER BY h.fecha DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial Clínico</title>
    <link rel="stylesheet" href="../assets/estilos.css">
</head>
<body>
    <h1>Historial Clínico General</h1>
    <a href="registrar.php">Agregar nueva entrada</a>
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Paciente</th>
                <th>Descripción</th>
                <th>Receta</th>
                <th>Archivo</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['fecha']) ?></td>
                <td><?= htmlspecialchars($row['paciente']) ?></td>
                <td><?= nl2br(htmlspecialchars($row['descripcion'])) ?></td>
                <td><?= nl2br(htmlspecialchars($row['receta'])) ?></td>
                <td>
                    <?php if ($row['archivo']): ?>
                        <a href="uploads/<?= htmlspecialchars($row['archivo']) ?>" target="_blank">Ver archivo</a>
                    <?php else: ?>
                        —
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <a href="../index.php">Volver al panel</a>
</body>
</html>