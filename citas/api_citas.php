<?php
// citas/api_citas.php
require_once '../includes/db.php';
session_start();

if (!isset($_SESSION['id'])) {
    http_response_code(401);
    echo json_encode(["error" => "No autorizado"]);
    exit;
}

$sql = "SELECT c.id, c.fecha, c.hora, c.motivo, c.estado,
               p.nombre AS paciente, u.nombre AS doctor
        FROM citas c
        JOIN pacientes p ON c.id_paciente = p.id
        JOIN usuarios u ON c.id_usuario = u.id";
$result = $conn->query($sql);

$eventos = [];
while ($row = $result->fetch_assoc()) {
    $eventos[] = [
        'id' => $row['id'],
        'title' => $row['paciente'] . ' con ' . $row['doctor'],
        'start' => $row['fecha'] . 'T' . $row['hora'],
        'descripcion' => $row['motivo'] . ' (' . $row['estado'] . ')',
        'paciente' => $row['paciente'], // 👈 clave para mostrar en el modal
    ];
}

header('Content-Type: application/json');
echo json_encode($eventos);
