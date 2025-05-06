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
$paciente = $stmt->get_result()->fetch_assoc();

if (!$paciente) {
    echo "Paciente no encontrado.";
    exit;
}

$stmt = $conn->prepare("SELECT * FROM historiales WHERE id_paciente = ? ORDER BY fecha DESC");
$stmt->bind_param("i", $id);
$stmt->execute();
$historiales = $stmt->get_result();

$stmt = $conn->prepare("SELECT c.fecha, c.hora, c.motivo, c.estado, u.nombre AS doctor
                        FROM citas c JOIN usuarios u ON c.id_usuario = u.id
                        WHERE c.id_paciente = ? ORDER BY c.fecha DESC");
$stmt->bind_param("i", $id);
$stmt->execute();
$citas = $stmt->get_result();

$titulo = 'Perfil del Paciente';

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
        max-width: 900px;
        margin: auto;
        background-color: #fff;
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
    }

    h1 {
        font-size: 26px;
        color: #1f3a93;
        margin-bottom: 25px;
    }

    h2 {
        color: #34495e;
        margin-top: 40px;
        font-size: 22px;
        border-bottom: 2px solid #ccc;
        padding-bottom: 5px;
    }

    p {
        margin: 10px 0;
        color: #2c3e50;
        font-size: 15px;
    }

    ul {
        list-style-type: none;
        padding-left: 0;
    }

    li {
        margin-bottom: 15px;
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        font-size: 14.5px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
    }

    a {
        color: #1f3a93;
        text-decoration: none;
        font-weight: 500;
    }

    a:hover {
        text-decoration: underline;
    }

    .btn-volver {
        display: inline-block;
        margin-top: 30px;
        color: #1f3a93;
        font-weight: 600;
        text-decoration: none;
        border: 2px solid #1f3a93;
        padding: 10px 18px;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .btn-volver:hover {
        background-color: #1f3a93;
        color: white;
    }

    .section {
        margin-bottom: 30px;
    }
</style>

<div class="main-panel">
    <h1>Perfil de <?= htmlspecialchars($paciente['nombre']) ?></h1>

    <div class="section">
        <p><strong>Fecha de nacimiento:</strong> <?= $paciente['fecha_nacimiento'] ?></p>
        <p><strong>Teléfono:</strong> <?= htmlspecialchars($paciente['telefono']) ?></p>
        <p><strong>Correo:</strong> <?= htmlspecialchars($paciente['correo']) ?></p>
        <p><strong>Dirección:</strong> <?= nl2br(htmlspecialchars($paciente['direccion'])) ?></p>
        <p><strong>Género:</strong> <?= $paciente['genero'] ?></p>
        <p><strong>Tipo de sangre:</strong> <?= htmlspecialchars($paciente['tipo_sangre']) ?></p>
        <p><strong>Alergias:</strong> <?= nl2br(htmlspecialchars($paciente['alergias'])) ?></p>
        <p><strong>Enfermedades crónicas:</strong> <?= nl2br(htmlspecialchars($paciente['enfermedades'])) ?></p>
        <p><strong>Antecedentes familiares:</strong> <?= nl2br(htmlspecialchars($paciente['antecedentes'])) ?></p>
    </div>

    <h2>Historial Clínico</h2>
    <ul>
        <?php while ($h = $historiales->fetch_assoc()): ?>
            <li>
                <strong><?= $h['fecha'] ?></strong>: <?= htmlspecialchars($h['descripcion']) ?>
                <?php if ($h['archivo']): ?>
                    <br>📄 <a href="../uploads/<?= $h['archivo'] ?>" target="_blank">Ver archivo</a>
                <?php endif; ?>
                <?php if ($h['receta']): ?>
                    <br>📝 <a href="../historial/receta_pdf.php?id=<?= $h['id'] ?>" target="_blank">Generar receta</a>
                <?php endif; ?>
            </li>
        <?php endwhile; ?>
    </ul>

    <h2>Citas</h2>
    <ul>
        <?php while ($c = $citas->fetch_assoc()): ?>
            <li>
                <strong><?= $c['fecha'] ?> <?= $c['hora'] ?></strong> con <b><?= htmlspecialchars($c['doctor']) ?></b> — <i><?= $c['estado'] ?></i><br>
                Motivo: <?= htmlspecialchars($c['motivo']) ?>
            </li>
        <?php endwhile; ?>
    </ul>

    <a class="btn-volver" href="listado.php"><i class="fa fa-arrow-left"></i> Volver al listado</a>
</div>


