<?php
// citas/calendario.php
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/locales/es.js"></script>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        #calendar { max-width: 900px; margin: auto; }
    </style>
</head>
<body>
    <h1>Calendario de Citas</h1>
    <div id="calendar"></div>
    <a href="listado.php">Volver al listado</a>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'es',
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: 'api_citas.php',
                eventClick: function(info) {
                    alert(info.event.title + "\n" + info.event.extendedProps.descripcion);
                }
            });
            calendar.render();
        });
    </script>
</body>
</html>