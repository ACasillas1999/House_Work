<?php
require_once '../includes/db.php';
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Calendario de Citas</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- FullCalendar con index.global para que funcione -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/locales-all.global.min.js"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #ecf0f1;
            margin: 0;
            padding: 40px;
        }

        h1 {
            text-align: center;
            color: #1f3a93;
            font-size: 28px;
            margin-bottom: 30px;
        }

        .btn-volver {
            display: block;
            background-color: #1f3a93;
            color: #fff;
            padding: 10px 18px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.3s ease;
            margin: 20px auto 40px;
            width: fit-content;
        }

        .btn-volver:hover {
            background-color: #005fc4;
        }

        #calendar {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            padding: 20px;
            max-width: 1000px;
            margin: auto;
            min-height: 600px;
        }

        .fc-toolbar-title {
            color: #1f3a93;
        }

        .fc-button-primary {
            background-color: #1f3a93;
            border-color: #1f3a93;
        }

        .fc-button-primary:hover {
            background-color: #005fc4;
            border-color: #005fc4;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0;
            top: 0;
            width: 100vw;
            height: 100vh;
            overflow: auto;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: #fff;
            margin: 10% auto;
            padding: 30px;
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.2);
        }

        .modal-content h2 {
            margin-top: 0;
            color: #1f3a93;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: #000;
        }

        .modal p {
            margin-bottom: 12px;
        }
    </style>
</head>
<body>

<h1>Calendario de Citas</h1>
<a class="btn-volver" href="listado.php"><i class="fa fa-arrow-left"></i> Volver al listado</a>

<div id="calendar"></div>

<!-- Modal -->
<div id="infoModal" class="modal">
    <div class="modal-content">
        <span class="close" id="cerrarModal">&times;</span>
        <h2 id="modalTitle">Título</h2>
        <p><strong>Paciente:</strong> <span id="modalPaciente">—</span></p>
        <p><strong>Motivo:</strong> <span id="modalDescripcion">—</span></p>
        <p><strong>Fecha:</strong> <span id="modalFecha">—</span></p>
        <p><strong>Hora:</strong> <span id="modalHora">—</span></p>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');
        var calendar = new Calendar(calendarEl, {
            locale: 'es',
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: 'api_citas.php',
            eventClick: function(info) {
                document.getElementById('modalTitle').innerText = info.event.title;
                document.getElementById('modalPaciente').innerText = info.event.extendedProps.paciente || '—';
                document.getElementById('modalDescripcion').innerText = info.event.extendedProps.descripcion || '—';
                document.getElementById('modalFecha').innerText = info.event.startStr.split("T")[0];
                document.getElementById('modalHora').innerText = info.event.startStr.split("T")[1]?.substring(0,5) || '—';
                document.getElementById('infoModal').style.display = 'block';
            }
        });
        calendar.render();

        // Cerrar modal
        document.getElementById('cerrarModal').onclick = function() {
            document.getElementById('infoModal').style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target === document.getElementById('infoModal')) {
                document.getElementById('infoModal').style.display = 'none';
            }
        }
    });
</script>

</body>
</html>
