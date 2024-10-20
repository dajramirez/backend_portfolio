<?php
session_start();

// Si el usuario no está autenticado redirigimos al login
if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit();
}

// Conectamos con la base de datos
require_once '../includes/config.php';

// Obtenemos el usuario actual
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$stmt->bindParam(':id', $_SESSION['user_id']);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Si no se encuentra el usuario redirigimos al login
if (!$user) {
  header('Location: login.php');
  exit();
}

// Si se recibe una solicitud POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Obtenemos los datos del formulario
  $new_email = $_POST['email'];
  $new_password = $_POST['password'];
  $password_confirm = $_POST['password_confirm'];

  // Preparamos un array para guardar los errores si los hubiera
  $errors = [];

  // Validaciones básicas
  if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "El correo electrónico introducido no es válido.";
  }

  if (!empty($new_password) && $new_password !== $password_confirm) {
    $errors[] = "Las contraseñas no coinciden.";
  }

  if (empty($errors)) {

    $pdo->beginTransaction();

    // Actualizamos el email
    $stmt = $pdo->prepare("UPDATE users SET email = :email WHERE id = :id");
    $stmt->bindParam(':email', $new_email);
    $stmt->bindParam(':id', $_SESSION['user_id']);
    $stmt->execute();

    // Si la contraseña no está vacía, la encriptamos y actualizamos
    if (!empty($new_password)) {
      $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
      $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE id = :id");
      $stmt->bindParam(':password', $hashedPassword);
      $stmt->bindParam(':id', $_SESSION['user_id']);
      $stmt->execute();
    }

    $pdo->commit();

    // Después de actualizar destruimos la sesión y redirigimos al login con un mensaje para mayor seguridad
    session_destroy();
    header('Location: login.php?message=profile_updated');
    exit();
  }
}

?>

<!DOCTYPE html>
<html lang="es-ES">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="../css/styles.css">
  <title>Perfil de Usuario</title>
</head>

<body>

  <h1>Perfil de Usuario</h1>

  <p><strong>Correo electrónico:</strong> <?= htmlspecialchars($user['email']); ?></p>
  <p><strong>Miembro desde:</strong> <?= htmlspecialchars($user['created_at']) ?></p>

  <h2>Editar perfil</h2>

  <!-- Mostrar errores -->
  <?php if (!empty($errors)) : ?>
    <ul class='error'>
      <?php foreach ($errors as $error) : ?>
        <li><?= $error; ?></li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>

  <form action="profile.php" method="POST">
    <label for="email">Correo electrónico:</label><br>
    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br><br>

    <label for="password">Nueva contraseña (opcional):</label><br>
    <input type="password" id="password" name='password'><br><br>

    <label for="password_confirm">Confirmar nueva contraseña:</label><br>
    <input type="password" id="password_confirm" name="password_confirm"><br><br>

    <input type="submit" value="Guardar cambios">
  </form>

  <p><a href="logout.php">Cerrar sesión</a></p>

</body>

</html>