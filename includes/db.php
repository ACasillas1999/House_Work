<?php
$host = 'sql3.freesqldatabase.com';
$user = 'sql3777106';
$pass = 'RVlewHdErv';
$db = 'sql3777106';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
