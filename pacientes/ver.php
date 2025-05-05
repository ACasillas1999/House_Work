<?php
// pacientes/ver.php
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

// Obtener info del paciente
$stmt = $conn->prepare("SELECT * FROM pacientes WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$paciente = $stmt->get_result()->fetch_assoc();

if (!$paciente) {
    echo "Paciente no encontrado.";
    exit;
}

// Obtener historial del paciente
$stmt = $conn->prepare("SELECT * FROM historiales WHERE id_paciente = ? ORDER BY fecha DESC");
$stmt->bind_param("i", $id);
$stmt->execute();
$historiales = $stmt->get_result();

// Obtener citas del paciente
$stmt = $conn->prepare("SELECT c.fecha, c.hora, c.motivo, c.estado, u.nombre AS doctor
                        FROM citas c JOIN usuarios u ON c.id_usuario = u.id
                        WHERE c.id_paciente = ? ORDER BY c.fecha DESC");
$stmt->bind_param("i", $id);
$stmt->execute();
$citas = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil del Paciente</title>
    <link rel="stylesheet" href="../assets/estilos.css">
    <link rel="stylesheet" href="../assets/estilos.css">
</head>
<body>
    <h1>Perfil de <?= htmlspecialchars($paciente['nombre']) ?></h1>
    <p><strong>Fecha de nacimiento:</strong> <?= $paciente['fecha_nacimiento'] ?></p>
    <p><strong>Teléfono:</strong> <?= htmlspecialchars($paciente['telefono']) ?></p>
    <p><strong>Correo:</strong> <?= htmlspecialchars($paciente['correo']) ?></p>
    <p><strong>Dirección:</strong> <?= nl2br(htmlspecialchars($paciente['direccion'])) ?></p>
    <p><strong>Género:</strong> <?= $paciente['genero'] ?></p>
    <p><strong>Tipo de sangre:</strong> <?= htmlspecialchars($paciente['tipo_sangre']) ?></p>
    <p><strong>Alergias:</strong> <?= nl2br(htmlspecialchars($paciente['alergias'])) ?></p>
    <p><strong>Enfermedades crónicas:</strong> <?= nl2br(htmlspecialchars($paciente['enfermedades'])) ?></p>
    <p><strong>Antecedentes familiares:</strong> <?= nl2br(htmlspecialchars($paciente['antecedentes'])) ?></p>

    <h2>Historial Clínico</h2>
    <ul>
        <?php while ($h = $historiales->fetch_assoc()): ?>
            <li>
                <strong><?= $h['fecha'] ?></strong>: <?= htmlspecialchars($h['descripcion']) ?>
                <?php if ($h['archivo']): ?>
                    - <a href="../uploads/<?= $h['archivo'] ?>" target="_blank">Ver archivo</a>
                <?php endif; ?>
                <?php if ($h['receta']): ?>
                    - <a href="../historial/receta_pdf.php?id=<?= $h['id'] ?>" target="_blank">Generar receta</a>
                <?php endif; ?>
            </li>
        <?php endwhile; ?>
    </ul>

    <h2>Citas</h2>
    <ul>
        <?php while ($c = $citas->fetch_assoc()): ?>
            <li>
                <strong><?= $c['fecha'] ?> <?= $c['hora'] ?></strong> con <?= $c['doctor'] ?> — <?= $c['estado'] ?><br>
                Motivo: <?= htmlspecialchars($c['motivo']) ?>
            </li>
        <?php endwhile; ?>
    </ul>

    <a href="listado.php">Volver al listado</a>
</body>
</html>
