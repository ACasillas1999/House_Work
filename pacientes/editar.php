<?php
// pacientes/editar.php
require_once '../includes/db.php';
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: listado.php");
    exit;
}

// Obtener datos actuales
$stmt = $conn->prepare("SELECT * FROM pacientes WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$paciente = $result->fetch_assoc();

if (!$paciente) {
    echo "Paciente no encontrado.";
    exit;
}

// Actualizar datos si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $direccion = $_POST['direccion'];
    $genero = $_POST['genero'];
    $tipo_sangre = $_POST['tipo_sangre'];
    $alergias = $_POST['alergias'];
    $enfermedades = $_POST['enfermedades'];
    $antecedentes = $_POST['antecedentes'];

    $stmt = $conn->prepare("UPDATE pacientes SET nombre=?, fecha_nacimiento=?, telefono=?, correo=?, direccion=?, genero=?, tipo_sangre=?, alergias=?, enfermedades=?, antecedentes=? WHERE id=?");
    $stmt->bind_param("ssssssssssi", $nombre, $fecha_nacimiento, $telefono, $correo, $direccion, $genero, $tipo_sangre, $alergias, $enfermedades, $antecedentes, $id);
    $stmt->execute();

    header("Location: listado.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Paciente</title>
    <link rel="stylesheet" href="../assets/estilos.css">
</head>
<body>
    <h1>Editar Paciente</h1>
    <form method="POST">
        <label>Nombre completo:</label>
        <input type="text" name="nombre" value="<?= htmlspecialchars($paciente['nombre']) ?>" required>

        <label>Fecha de nacimiento:</label>
        <input type="date" name="fecha_nacimiento" value="<?= $paciente['fecha_nacimiento'] ?>" required>

        <label>Teléfono:</label>
        <input type="text" name="telefono" value="<?= htmlspecialchars($paciente['telefono']) ?>">

        <label>Correo electrónico:</label>
        <input type="email" name="correo" value="<?= htmlspecialchars($paciente['correo']) ?>">

        <label>Dirección:</label>
        <textarea name="direccion"><?= htmlspecialchars($paciente['direccion']) ?></textarea>

        <label>Género:</label>
        <select name="genero">
            <option value="Masculino" <?= $paciente['genero'] === 'Masculino' ? 'selected' : '' ?>>Masculino</option>
            <option value="Femenino" <?= $paciente['genero'] === 'Femenino' ? 'selected' : '' ?>>Femenino</option>
            <option value="Otro" <?= $paciente['genero'] === 'Otro' ? 'selected' : '' ?>>Otro</option>
        </select>

        <label>Tipo de sangre:</label>
        <input type="text" name="tipo_sangre" value="<?= htmlspecialchars($paciente['tipo_sangre']) ?>">

        <label>Alergias:</label>
        <textarea name="alergias"><?= htmlspecialchars($paciente['alergias']) ?></textarea>

        <label>Enfermedades crónicas:</label>
        <textarea name="enfermedades"><?= htmlspecialchars($paciente['enfermedades']) ?></textarea>

        <label>Antecedentes familiares:</label>
        <textarea name="antecedentes"><?= htmlspecialchars($paciente['antecedentes']) ?></textarea>

        <button type="submit">Actualizar</button>
    </form>
    <a href="listado.php">Volver al listado</a>
</body>
</html>