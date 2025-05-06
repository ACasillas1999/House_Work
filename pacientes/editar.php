<?php
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

$stmt = $conn->prepare("SELECT * FROM pacientes WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$paciente = $result->fetch_assoc();

if (!$paciente) {
    echo "Paciente no encontrado.";
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

    $stmt = $conn->prepare("UPDATE pacientes SET nombre=?, fecha_nacimiento=?, telefono=?, correo=?, direccion=?, genero=?, tipo_sangre=?, alergias=?, enfermedades=?, antecedentes=? WHERE id=?");
    $stmt->bind_param("ssssssssssi", $nombre, $fecha_nacimiento, $telefono, $correo, $direccion, $genero, $tipo_sangre, $alergias, $enfermedades, $antecedentes, $id);
    $stmt->execute();

    header("Location: listado.php");
    exit;
}

$titulo = 'Editar Paciente';
//include '../includes/header.php';
?>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
    body {
        font-family: 'Inter', sans-serif;
        background-color: #ecf0f1;
        margin: 0;
        padding: 40px;
    }

    .main-panel {
        max-width: 800px;
        margin: auto;
        background-color: #fff;
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 6px 16px rgba(0,0,0,0.08);
    }

    h1 {
        color: #1f3a93;
        font-size: 26px;
        margin-bottom: 30px;
    }

    form {
        display: flex;
        flex-direction: column;
    }

    label {
        margin-bottom: 6px;
        font-weight: 600;
        color: #2c3e50;
    }

    input[type="text"],
    input[type="date"],
    input[type="email"],
    select,
    textarea {
        padding: 10px 12px;
        border: 1px solid #ccc;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 15px;
        background-color: #fdfdfd;
        box-sizing: border-box;
    }

    textarea {
        resize: vertical;
        min-height: 70px;
    }

    button[type="submit"] {
        background-color: #1f3a93;
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    button[type="submit"]:hover {
        background-color: #005fc4;
    }

    .btn-volver {
        display: inline-block;
        margin-top: 20px;
        color: #1f3a93;
        font-weight: 600;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .btn-volver:hover {
        color: #005fc4;
    }

    @media (max-width: 600px) {
        .main-panel {
            padding: 20px;
        }
    }
</style>

<div class="main-panel">
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

        <button type="submit"><i class="fa fa-save"></i> Actualizar</button>
    </form>
    <a class="btn-volver" href="listado.php"><i class="fa fa-arrow-left"></i> Volver al listado</a>
</div>

<!--<?php include '../includes/footer.php'; ?>-->
