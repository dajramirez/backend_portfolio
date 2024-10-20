<?php
session_start();

try {
  // Verficamos si el usuario está autenticado
  if (!isset($_SESSION['user_id'])) {
    // Si no está autenticado, redirigimos al formulario de login
    header('Location: login.php');
    exit();
  }

  // Obtenemos el usuario actual
  require_once '../includes/config.php';

  $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :user_id");
  $stmt->bindParam(':user_id', $_SESSION['user_id']);
  $stmt->execute();

  // Hacemos fetch y  verificamos si el usuario existe
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$user) {
    throw new Exception('Usuario no encontrado.');
  }

  // Regeneramos la ID  de la sesión tras el login exitoso
  session_regenerate_id(true);

  // Cerramos el statement
  $stmt = null;
} catch (PDOException $e) {
  // Aquí manejamos los errores relacionados con la base de datos
  echo "Error de base de datos: " . $e->getMessage();
  exit();
} catch (Exception $e) {
  // Aquí manejamos los errores generales
  echo "Error: " . $e->getMessage();
  exit();
} finally {
  // Cerramos la conexión a la base de datos
  if (isset($pdo)) {
    $pdo = null;
  }
}
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