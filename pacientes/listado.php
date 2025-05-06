<?php
require_once '../includes/db.php';
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

$result = $conn->query("SELECT * FROM pacientes ORDER BY nombre ASC");
$titulo = 'Listado de Pacientes';
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

        .btn-registrar,
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

        .btn-registrar:hover,
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

        .acciones a {
            margin-right: 10px;
            text-decoration: none;
            font-weight: 500;
            color: #1f3a93;
            transition: color 0.2s ease;
        }

        .acciones a:hover {
            color: #005fc4;
        }

        .dt-buttons {
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .btn-registrar, .btn-volver {
                display: block;
                margin-bottom: 15px;
            }
        }
    </style>
</head>
<body>

<div class="main-panel">
    <h1>Listado de Pacientes</h1>
    <a class="btn-volver" href="../index.php"><i class="fa fa-arrow-left"></i> Volver al Panel</a>
    <a class="btn-registrar" href="agregar.php"><i class="fa fa-user-plus"></i> Registrar nuevo paciente</a>

    <table id="tablaPacientes" class="display responsive nowrap" style="width:100%">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Fecha de Nacimiento</th>
                <th>Teléfono</th>
                <th>Correo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['nombre']) ?></td>
                <td><?= htmlspecialchars($row['fecha_nacimiento']) ?></td>
                <td><?= htmlspecialchars($row['telefono']) ?></td>
                <td><?= htmlspecialchars($row['correo']) ?></td>
                <td class="acciones">
                    <a href="ver.php?id=<?= $row['id'] ?>"><i class="fa fa-eye"></i> Ver</a>
                    <a href="editar.php?id=<?= $row['id'] ?>"><i class="fa fa-pen"></i> Editar</a>
                    <a href="eliminar.php?id=<?= $row['id'] ?>" onclick="return confirm('¿Desea eliminar este paciente?');"><i class="fa fa-trash"></i> Eliminar</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- JS de jQuery + DataTables + Buttons -->
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
        $('#tablaPacientes').DataTable({
            responsive: true,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'copy',
                    text: '<i class="fa fa-copy"></i> Copiar',
                    className: 'btn btn-sm'
                },
                {
                    extend: 'excel',
                    text: '<i class="fa fa-file-excel"></i> Excel',
                    className: 'btn btn-sm'
                },
                {
                    extend: 'pdf',
                    text: '<i class="fa fa-file-pdf"></i> PDF',
                    className: 'btn btn-sm'
                },
                {
                    extend: 'print',
                    text: '<i class="fa fa-print"></i> Imprimir',
                    className: 'btn btn-sm'
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
