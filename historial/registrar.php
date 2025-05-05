<?php
// historial/registrar.php
require_once '../includes/db.php';
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

$id_fijo = $_GET['id_paciente'] ?? null;

if ($id_fijo) {
    $pacientes = $conn->query("SELECT id, nombre FROM pacientes WHERE id = $id_fijo");
} else {
    $pacientes = $conn->query("SELECT id, nombre FROM pacientes ORDER BY nombre ASC");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_paciente = $_POST['id_paciente'];
    $descripcion = $_POST['descripcion'];
    $receta = $_POST['receta'];
    $archivo = null;

    if (!empty($_FILES['archivo']['name'])) {
        $nombre_archivo = time() . '_' . basename($_FILES['archivo']['name']);
        $ruta = '../uploads/' . $nombre_archivo;
        if (move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta)) {
            $archivo = $nombre_archivo;
        }
    }

    $stmt = $conn->prepare("INSERT INTO historiales (id_paciente, fecha, descripcion, receta, archivo) VALUES (?, NOW(), ?, ?, ?)");
    $stmt->bind_param("issss", $id_paciente, $descripcion, $receta, $archivo);
    $stmt->execute();

    header("Location: listado.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Entrada Clínica</title>
    <link rel="stylesheet" href="../assets/estilos.css">
</head>
<body>
    <h1>Nueva Entrada de Historial Clínico</h1>
    <form method="POST" enctype="multipart/form-data">
        <label>Paciente:</label>
        <select name="id_paciente" required <?= $id_fijo ? 'disabled' : '' ?>>
            <?php while ($p = $pacientes->fetch_assoc()): ?>
                <option value="<?= $p['id'] ?>" <?= ($id_fijo && $id_fijo == $p['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($p['nombre']) ?>
                </option>
            <?php endwhile; ?>
        </select>
        <?php if ($id_fijo): ?>
            <input type="hidden" name="id_paciente" value="<?= $id_fijo ?>">
        <?php endif; ?>

        <label>Descripción de la consulta:</label>
        <textarea name="descripcion" required></textarea>

        <label>Receta (opcional):</label>
        <textarea name="receta"></textarea>

        <label>Archivo adjunto (PDF, imagen, etc):</label>
        <input type="file" name="archivo" accept=".pdf,image/*">

        <button type="submit">Guardar Entrada</button>
    </form>
    <a href="listado.php">Volver al historial</a>
</body>
</html>