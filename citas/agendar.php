<?php
// citas/agendar.php
require_once '../includes/db.php';
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

// Obtener listas de pacientes y doctores
$pacientes = $conn->query("SELECT id, nombre FROM pacientes ORDER BY nombre ASC");
$doctores = $conn->query("SELECT id, nombre FROM usuarios WHERE rol = 'doctor' ORDER BY nombre ASC");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_paciente = $_POST['id_paciente'];
    $id_usuario = $_POST['id_usuario'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $motivo = $_POST['motivo'];

    $stmt = $conn->prepare("INSERT INTO citas (id_paciente, id_usuario, fecha, hora, motivo) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iisss", $id_paciente, $id_usuario, $fecha, $hora, $motivo);
    $stmt->execute();

    header("Location: listado.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agendar Cita</title>
    <link rel="stylesheet" href="../assets/estilos.css">
</head>
<body>
    <h1>Agendar Nueva Cita</h1>
    <form method="POST">
        <label>Paciente:</label>
        <select name="id_paciente" required>
            <?php while ($p = $pacientes->fetch_assoc()): ?>
                <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nombre']) ?></option>
            <?php endwhile; ?>
        </select>

        <label>Doctor:</label>
        <select name="id_usuario" required>
            <?php while ($d = $doctores->fetch_assoc()): ?>
                <option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['nombre']) ?></option>
            <?php endwhile; ?>
        </select>

        <label>Fecha:</label>
        <input type="date" name="fecha" required>

        <label>Hora:</label>
        <input type="time" name="hora" required>

        <label>Motivo:</label>
        <textarea name="motivo"></textarea>

        <button type="submit">Guardar Cita</button>
    </form>
    <a href="listado.php">Volver al listado</a>
</body>
</html>
