<?php
// pacientes/listado.php
require_once '../includes/db.php';
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

$result = $conn->query("SELECT * FROM pacientes ORDER BY nombre ASC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pacientes</title>
    <link rel="stylesheet" href="../assets/estilos.css">
</head>
<body>
    <h1>Listado de Pacientes</h1>
    <a href="agregar.php">Registrar nuevo paciente</a>
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Fecha de Nacimiento</th>
                <th>Teléfono</th>
                <th>Correo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['nombre']) ?></td>
                <td><?= htmlspecialchars($row['fecha_nacimiento']) ?></td>
                <td><?= htmlspecialchars($row['telefono']) ?></td>
                <td><?= htmlspecialchars($row['correo']) ?></td>
                <td>
                    <a href="ver.php?id=<?= $row['id'] ?>">Ver perfil</a> |
                    <a href="editar.php?id=<?= $row['id'] ?>">Editar</a> |
                    <a href="eliminar.php?id=<?= $row['id'] ?>" onclick="return confirm('¿Desea eliminar este paciente?');">Eliminar</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
