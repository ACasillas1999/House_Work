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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar_foto']) && isset($_FILES['foto'])) {
    $foto_nombre = $_FILES['foto']['name'];
    $foto_tmp = $_FILES['foto']['tmp_name'];
    $foto_extension = pathinfo($foto_nombre, PATHINFO_EXTENSION);
    $nuevo_nombre = "foto_{$id}." . strtolower($foto_extension);
    $ruta_destino = "../uploads/fotos/" . $nuevo_nombre;

    if (!is_dir("../uploads/fotos")) {
        mkdir("../uploads/fotos", 0777, true);
    }

    if (move_uploaded_file($foto_tmp, $ruta_destino)) {
        $stmt = $conn->prepare("UPDATE pacientes SET foto = ? WHERE id = ?");
        $stmt->bind_param("si", $nuevo_nombre, $id);
        $stmt->execute();
        header("Location: perfil.php?id=" . $id); // recargar para ver cambios
        exit;
    } else {
        echo "<script>alert('Error al subir la foto');</script>";
    }
}


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
    margin: 0;
    background-color: #ecf0f1;
}

.perfil-container {
    display: flex;
    min-height: 100vh;
}

/* SIDEBAR REDISEÑADA */

.perfil-sidebar {
    width: 180px;
    background-color: #ffffff;
    border-right: 1px solid #ccc;
    padding: 30px 10px;
}
.sidebar {
        background-color: #1f3a93;
        width: 240px;
        height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        border-right: 1px solid #ccc;
        padding: 30px 10px;
        position: fixed;
        top: 0;
        left: 0;
        overflow-y: auto;
        box-shadow: 2px 0 5px rgba(0,0,0,0.1);
    }

    .sidebar .sidebar-header {
        
        text-align: center;
        margin-bottom: 30px;
    }

    .sidebar .sidebar-header h2 {
        font-size: 18px;
        color: #fff;
        margin-bottom: 0;
        letter-spacing: 0.5px;
    }

    .sidebar .nav-links {
        display: flex;
        flex-direction: column;
        gap: 5px;
        padding: 0 20px;
    }

    .sidebar a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 15px;
        color: white;
        text-decoration: none;
        border-radius: 6px;
        transition: background 0.3s, transform 0.2s;
        font-size: 15px;
        white-space: nowrap;
    }

    .sidebar a i {
        font-size: 16px;
        width: 20px;
        text-align: center;
    }

    .sidebar a:hover {
        background-color: rgb(0, 106, 212);
        transform: translateX(2px);
    }

.perfil-btn {
    background-color: #f5f7fa;
    color: #1f3a93;
    padding: 12px;
    margin-bottom: 15px;
    border: 2px solid #1f3a93;
    border-radius: 8px;
    text-align: center;
    font-weight: 600;
    cursor: pointer;
    transition: 0.3s;
}

.perfil-btn:hover {
    background-color: #1f3a93;
    color: white;
}

.perfil-content {
    margin-left: 240px; /* <-- esto evita que se encime */
    padding: 40px;
    flex: 1;
}


.perfil-top {
    display: flex;
    justify-content: space-between;
    gap: 40px;
    margin-bottom: 40px;
    background-color: #fff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
}

.perfil-info {
    flex: 1;
    color: #2c3e50;
    font-size: 15px;
}

.perfil-info h1 {
    font-size: 26px;
    color: #1f3a93;
    margin-bottom: 20px;
}

.perfil-foto {
    width: 120px;
}

.perfil-foto-box {
    width: 100%;
    height: 120px;
    border: 2px solid #1f3a93;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #34495e;
    background-color: #f8f9fa;
    font-weight: bold;
}

.perfil-bottom {
    display: flex;
    gap: 30px;
}

.perfil-panel {
    flex: 1;
    background-color: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
}

.perfil-panel h2 {
    color: #34495e;
    font-size: 20px;
    margin-bottom: 20px;
}

ul {
    list-style: none;
    padding: 0;
}

li {
    background-color: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 10px;
    font-size: 14px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
}

@media (max-width: 768px) {
    .sidebar {
        position: fixed;
        left: -260px; /* Oculta la barra fuera de vista */
        transition: left 0.3s ease;
        z-index: 1000;
    }

    .sidebar.active {
        left: 0; /* Muestra la barra si tiene la clase "active" */
    }

    .perfil-content {
        margin-left: 0 !important; /* El contenido usa todo el ancho */
        padding: 20px;
    }

    .sidebar-toggle {
        position: fixed;
        top: 20px;
        left: 20px;
        background-color: #1f3a93;
        color: white;
        border: none;
        padding: 10px 14px;
        border-radius: 6px;
        font-size: 18px;
        cursor: pointer;
        z-index: 1100;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    }
}

</style>

<div class="perfil-container">



  <!--<div class="perfil-sidebar">
        <div class="perfil-btn"><i class="fa fa-user"></i> <?= htmlspecialchars($_SESSION['nombre']) ?></div>
        <div class="perfil-btn"><a href="../index.php">Volver al inicio</a></div>
    </div>-->

    <button class="sidebar-toggle" onclick="document.querySelector('.sidebar').classList.toggle('active')">
    <i class="fas fa-bars"></i>
</button>

<div class="sidebar">
        <div>
            <div class="sidebar-header">
                <h2>CLÍNICA</h2>
            </div>
            <div class="nav-links">
    <a href="pacientes/listado.php"><i class="fa fa-users"></i>Pacientes</a>
    <a href="citas/listado.php"><i class="fa fa-calendar-check"></i>Citas</a>
    <a href="citas/calendario.php"><i class="fa fa-calendar-days"></i>Calendario</a>
    <a href="historial/listado.php"><i class="fa fa-file-medical-alt"></i>Historial</a>
    <?php if ($_SESSION['rol'] === 'admin'): ?>
        <a href="usuarios/registro.php"><i class="fa fa-user-plus"></i>Registrar Usuario</a>
    <?php endif; ?>
    <?php if ($_SESSION['rol'] === 'doctor'): ?>
        <a href="panel_doctor.php"><i class="fa fa-stethoscope"></i>Mi Panel Médico</a>
    <?php endif; ?>
    
    <!-- NUEVO BOTÓN PARA VOLVER AL LISTADO -->
    <a href="listado.php"><i class="fa fa-arrow-left"></i>Volver al listado</a>
</div>

        </div>
        <div class="logout">
            <a href="usuarios/logout.php"><i class="fa fa-sign-out-alt"></i> Cerrar sesión</a>
        </div>
    </div>


    <div class="perfil-content">
        <div class="perfil-top">
            <div class="perfil-info">
                <h1><?= htmlspecialchars($paciente['nombre']) ?></h1>
                <p><strong>Fecha de nacimiento:</strong> <?= $paciente['fecha_nacimiento'] ?></p>
                <p><strong>Teléfono:</strong> <?= htmlspecialchars($paciente['telefono']) ?></p>
                <p><strong>Correo:</strong> <?= htmlspecialchars($paciente['correo']) ?></p>
                <p><strong>Dirección:</strong> <?= nl2br(htmlspecialchars($paciente['direccion'])) ?></p>
                <p><strong>Género:</strong> <?= $paciente['genero'] ?></p>
                <p><strong>Tipo de sangre:</strong> <?= htmlspecialchars($paciente['tipo_sangre']) ?></p>
            </div>
            <div class="perfil-foto">
            <form method="POST" enctype="multipart/form-data">
    <?php if (!empty($paciente['foto'])): ?>
        <img src="../uploads/fotos/<?= $paciente['foto'] ?>" alt="Foto del paciente" style="width: 100%; border-radius: 6px; margin-bottom: 8px;">
    <?php else: ?>
        <p style="text-align:center;">Sin foto</p>
    <?php endif; ?>

    <input type="file" name="foto" accept="image/*" style="font-size: 12px; margin-top: 5px;">
    <button type="submit" name="guardar_foto" style="margin-top: 5px; padding: 4px 8px; font-size: 12px;">Actualizar</button>
</form>

            </div>
        </div>

        <div class="perfil-bottom">
            <div class="perfil-panel">
                <h2><i class="fas fa-notes-medical"></i> Resumen de citas</h2>
                <ul>
                    <?php while ($c = $citas->fetch_assoc()): ?>
                        <li>
                            <strong><?= $c['fecha'] ?> <?= $c['hora'] ?></strong> con <b><?= htmlspecialchars($c['doctor']) ?></b><br>
                            <em><?= $c['estado'] ?></em><br>
                            Motivo: <?= htmlspecialchars($c['motivo']) ?>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>

            <div class="perfil-panel">
                <h2><i class="fas fa-file-medical-alt"></i> Generadores de documentos</h2>
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
            </div>
        </div>
    </div>
</div>