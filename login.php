<?php
session_start();
require_once 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['correo'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE correo = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($user = $res->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            $_SESSION["id"] = $user["id"];
            $_SESSION["nombre"] = $user["nombre"];
            $_SESSION["rol"] = $user["rol"];
            header("Location: index.php");
            exit;
        }
    }
    $error = "Correo o contraseña incorrectos.";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="assets/estilos.css">
    <style>
        .login-container {
            max-width: 400px;
            margin: 5vh auto;
            padding: 40px 30px;
            background: #ffffff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
            text-align: center;
        }
        .login-container h1 {
            margin-bottom: 30px;
            color: #1f3a93;
            font-size: 26px;
        }
        .login-container img {
            width: 75%;
            margin-bottom: 15px;
        }
        .login-container input[type="email"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 15px;
        }
        .login-container button {
            width: 100%;
            padding: 12px;
            background-color: #1f3a93;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
        }
        .login-container button:hover {
            background-color: #34495e;
        }
        @media (max-width: 480px) {
            .login-container {
                padding: 30px 20px;
            }
        }
        #particles-js {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: -1;
    background: #ecf0f1;
}

    </style>
</head>
<body>
<div id="particles-js"></div>

    <div class="login-container">
        <img src="assets/logo-clinica.png" alt="Logo Clínica">
        <h1>Acceso al Sistema</h1>
        <form method="POST" action="login.php">
            <input type="email" name="correo" placeholder="Correo electrónico" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Iniciar Sesión</button>
        </form>
    </div>
</body>
</html>

<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
<script>
  particlesJS("particles-js", {
    particles: {
      number: { value: 80 },
      color: { value: "#1f3a93" },
      shape: { type: "circle" },
      opacity: { value: 0.4 },
      size: { value: 3 },
      line_linked: {
        enable: true,
        distance: 150,
        color: "#1f3a93",
        opacity: 0.2,
        width: 1
      },
      move: { enable: true, speed: 2 }
    },
    interactivity: {
      detect_on: "canvas",
      events: {
        onhover: { enable: true, mode: "grab" },
        onclick: { enable: true, mode: "push" }
      }
    },
    retina_detect: true
  });
</script>
