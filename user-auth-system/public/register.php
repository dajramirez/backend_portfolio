<?php
session_start();

// Si el usuario ya está autenticado, lo redirigimos al index
if (isset($_SESSION['user_id'])) {
  header('Location: index.php');
  exit();
}

// Verificamos si el método de la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Conectamos con la base de datos
  require_once '../includes/config.php';

  // Obtenemos los datos del formulario
  $email = $_POST['email'];
  $password = $_POST['password'];
  $password_confirm = $_POST['password_confirm'];

  //Verificamos si el email ya está en uso
  $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
  $stmt->execute([$email]);

  if ($stmt->rowCount() > 0) {
    echo 'Este correo ya está registrado.';
  } else {
    // Validación simple
    if (!empty($email) && !empty($password) && !empty($password_confirm)) {
      if ($password === $password_confirm) {
        //Encriptamos la contraseña
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insertamos el nuevo usuario en la base de datos
        $stmt = $pdo->prepare("INSERT INTO users (email, password) VALUES (:email, :password)");
        $stmt->bindParam('email', $email);
        $stmt->bindParam('password', $hashedPassword);

        if ($stmt->execute()) {
          // Si se registra, redirigimos al login
          header('Location: login.php');
          exit();
        } else {
          echo "Error al registrar. Inténtelo de nuevo.";
        }
      } else {
        $error = "Las contraseñas no coinciden.";
      }
    } else {
      $error = "Error. Complete todos los campos.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="es-ES">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registro de usuarios</title>
</head>

<body>

  <h1>Registrarse</h1>

  <?php
  if (isset($error)) {
    echo "<p class='error'>$error</p>";
  }
  ?>

  <form action="register.php" method="POST">
    <fieldset>
      <legend>Regístrate</legend>

      <label for="email">Correo electrónico:</label><br>
      <input type="email" id="email" name="email" required><br>

      <label for="password">Contraseña:</label><br>
      <input type="password" id="password" name="password" required><br>

      <label for="password">Confirmar contraseña:</label><br>
      <input type="password" id="password_confirm" name="password_confirm" required><br><br>

      <button type="submit">Registrarse</button>

    </fieldset>
  </form>

  <p>¿Ya tienes una cuenta?<a href="login.php">Inicia sesión aquí</a></p>

</body>

</html>