<?php
// usuarios/registro.php
require_once '../includes/db.php';
session_start();

if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $rol = $_POST['rol'];

    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, correo, password, rol) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nombre, $correo, $password, $rol);
    $stmt->execute();

    header("Location: ../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Usuario</title>
    <link rel="stylesheet" href="../assets/estilos.css">
</head>
<body>
    <h1>Registro de Nuevo Usuario</h1>
    <form method="POST">
        <label>Nombre completo:</label>
        <input type="text" name="nombre" required>

        <label>Correo electrónico:</label>
        <input type="email" name="correo" required>

        <label>Contraseña:</label>
        <input type="password" name="password" required>

        <label>Rol:</label>
        <select name="rol" required>
            <option value="admin">Administrador</option>
            <option value="doctor">Doctor</option>
            <option value="recepcion">Recepcionista</option>
        </select>

        <button type="submit">Registrar Usuario</button>
    </form>
    <a href="../index.php">Volver al panel</a>
</body>
</html>
