<?php
function requiere_rol($rol_permitido) {
    if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== $rol_permitido) {
        header("Location: ../index.php");
        exit;
    }
}

function requiere_roles($roles_permitidos = []) {
    if (!isset($_SESSION['rol']) || !in_array($_SESSION['rol'], $roles_permitidos)) {
        header("Location: ../index.php");
        exit;
    }
}
?>


<!----Poner lo siguiente en todos los modulos---->

<!--

require_once '../includes/permisos.php';
requiere_rol('admin'); // Solo admins
// o
requiere_roles(['admin', 'recepcion']); // Admins y recepcionistas


---->