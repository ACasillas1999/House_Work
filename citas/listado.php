<?php
require_once '../includes/db.php';
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

$sql = "SELECT c.id, c.fecha, c.hora, c.motivo, c.estado, 
               p.nombre AS paciente, u.nombre AS doctor
        FROM citas c
        JOIN pacientes p ON c.id_paciente = p.id
        JOIN usuarios u ON c.id_usuario = u.id
        ORDER BY c.fecha, c.hora";
$result = $conn->query($sql);
$titulo = 'Agenda de Citas';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $titulo ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- DataTables + Buttons -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #ecf0f1;
            margin: 0;
            padding: 40px;
        }

        h1 {
            color: #1f3a93;
            font-size: 28px;
            margin-bottom: 20px;
        }

        .btn-agregar,
        .btn-volver {
            display: inline-block;
            background-color: #1f3a93;
            color: #fff;
            padding: 10px 18px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.3s ease;
            margin-right: 15px;
            margin-bottom: 25px;
        }

        .btn-agregar:hover,
        .btn-volver:hover {
            background-color: #005fc4;
        }

        table.dataTable {
            border-collapse: collapse !important;
            background-color: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        table.dataTable thead th {
            background-color: #1f3a93;
            color: white;
            font-weight: 600;
            font-size: 15px;
        }

        table.dataTable tbody td {
            font-size: 14px;
        }

        .dt-buttons {
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .btn-agregar, .btn-volver {
                display: block;
                margin-bottom: 15px;
            }
        }

        .btn-calendario {
    display: inline-block;
    background-color: #34495e;
    color: #fff;
    padding: 10px 18px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: background 0.3s ease;
    margin-right: 15px;
    margin-bottom: 25px;
}

.btn-calendario:hover {
    background-color: #2c3e50;
}

    </style>
</head>
<body>

<h1>Agenda de Citas</h1>
<a class="btn-volver" href="../index.php"><i class="fa fa-arrow-left"></i> Volver al Panel</a>
<a class="btn-agregar" href="agendar.php"><i class="fa fa-calendar-plus"></i> Agendar nueva cita</a>
<a class="btn-calendario" href="calendario.php"><i class="fa fa-calendar"></i> Ver calendario</a>

<table id="tablaCitas" class="display responsive nowrap" style="width:100%">
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Paciente</th>
            <th>Doctor</th>
            <th>Motivo</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['fecha']) ?></td>
            <td><?= htmlspecialchars($row['hora']) ?></td>
            <td><?= htmlspecialchars($row['paciente']) ?></td>
            <td><?= htmlspecialchars($row['doctor']) ?></td>
            <td><?= htmlspecialchars($row['motivo']) ?></td>
            <td><?= htmlspecialchars($row['estado']) ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<!-- JS: jQuery + DataTables + Buttons -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
    $(document).ready(function() {
        $('#tablaCitas').DataTable({
            responsive: true,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'copy',
                    text: '<i class="fa fa-copy"></i> Copiar'
                },
                {
                    extend: 'excel',
                    text: '<i class="fa fa-file-excel"></i> Excel'
                },
                {
                    extend: 'pdf',
                    text: '<i class="fa fa-file-pdf"></i> PDF'
                },
                {
                    extend: 'print',
                    text: '<i class="fa fa-print"></i> Imprimir'
                }
            ],
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            }
        });
    });
</script>

</body>
</html>
