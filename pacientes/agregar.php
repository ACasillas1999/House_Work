<?php
// Extensión de la tabla pacientes (ya lo debes aplicar en tu base de datos SQL):
// ALTER TABLE pacientes ADD tipo_sangre VARCHAR(10), ADD alergias TEXT, ADD enfermedades TEXT, ADD antecedentes TEXT;

// Actualización en agregar.php, editar.php y ver.php:
// Aquí ejemplo completo para agregar.php:

// pacientes/agregar.php
require_once '../includes/db.php';
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

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

    $stmt = $conn->prepare("INSERT INTO pacientes (nombre, fecha_nacimiento, telefono, correo, direccion, genero, tipo_sangre, alergias, enfermedades, antecedentes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssss", $nombre, $fecha_nacimiento, $telefono, $correo, $direccion, $genero, $tipo_sangre, $alergias, $enfermedades, $antecedentes);
    $stmt->execute();

    header("Location: listado.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Paciente</title>
    <link rel="stylesheet" href="../assets/estilos.css">
</head>
<body>
    <h1>Registrar Paciente</h1>
    <form method="POST">
        <label>Nombre completo:</label>
        <input type="text" name="nombre" required>

        <label>Fecha de nacimiento:</label>
        <input type="date" name="fecha_nacimiento" required>

        <label>Teléfono:</label>
        <input type="text" name="telefono">

        <label>Correo electrónico:</label>
        <input type="email" name="correo">

        <label>Dirección:</label>
        <textarea name="direccion"></textarea>

        <label>Género:</label>
        <select name="genero">
            <option value="Masculino">Masculino</option>
            <option value="Femenino">Femenino</option>
            <option value="Otro">Otro</option>
        </select>

        <label>Tipo de sangre:</label>
        <input type="text" name="tipo_sangre">

        <label>Alergias:</label>
        <textarea name="alergias"></textarea>

        <label>Enfermedades crónicas:</label>
        <textarea name="enfermedades"></textarea>

        <label>Antecedentes familiares:</label>
        <textarea name="antecedentes"></textarea>

        <button type="submit">Guardar</button>
    </form>
    <a href="listado.php">Volver al listado</a>
</body>
</html>
