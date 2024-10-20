<?php
session_start();

// Verficamos si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
  // Si no está autenticado, redirigimos al formulario de login
  header('Location: login.php');
  exit();
}

// Obtenemos el usuario actual
require_once '../includes/config.php';
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="es-EN">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Página de inicio - Sistema de Autenticación</title>
</head>

<body>

  <h1>Bienvenido a tu panel, <?= htmlspecialchars($user['email']) ?>!</h1>
  <p>Has iniciado sesión correctamente.</p>

  <!-- Opciones para el usuario autenticado. -->
  <ul>
    <li><a href="profile.php">Ver perfil</a></li>
    <li><a href="logout.php">Cerrar sesión</a></li>
  </ul>
</body>

</html>