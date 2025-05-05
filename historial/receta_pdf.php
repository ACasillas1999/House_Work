<?php
// historial/receta_pdf.php
require_once '../includes/db.php';
require_once '../vendor/autoload.php'; // Asegúrate de tener DomPDF instalado

use Dompdf\Dompdf;

session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    exit('ID de historial no proporcionado.');
}

// Obtener historial con receta
$stmt = $conn->prepare("SELECT h.fecha, h.receta, p.nombre AS paciente, u.nombre AS doctor
                         FROM historiales h
                         JOIN pacientes p ON h.id_paciente = p.id
                         JOIN citas c ON h.id_paciente = c.id_paciente
                         JOIN usuarios u ON c.id_usuario = u.id
                         WHERE h.id = ? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data || !$data['receta']) {
    exit('No hay receta disponible para esta entrada.');
}

$dompdf = new Dompdf();
$html = "
    <h2 style='text-align:center;'>Receta Médica</h2>
    <hr>
    <p><strong>Paciente:</strong> {$data['paciente']}</p>
    <p><strong>Doctor:</strong> {$data['doctor']}</p>
    <p><strong>Fecha:</strong> {$data['fecha']}</p>
    <h4>Indicaciones:</h4>
    <p>{$data['receta']}</p>
";

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("receta_{$data['paciente']}.pdf", ["Attachment" => true]);
