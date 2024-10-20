<?php
session_start();

// Si se ha recibido un mensaje de actualización, mostramos un mensaje de éxito
if (isset($_GET['message']) && $_GET['message'] === 'profile_updated') {
  echo "<p class='success'><strong>Perfil actualizado. Por favor, inicie sesión nuevamente.</strong></p>";
}

if (isset($_SESSION['user_id'])) {
  // Si el usuario está autenticado, redireccionamos al index.php
  header('Location: index.php');
  exit();
}

// Verificamos si es una solicitud POST (desde el formulario)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Conectamos con la base de datos
  require_once '../includes/config.php';

  // Filtramos y sanitizamos el email y la contraseña
  $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
  $password = $_POST['password'];

  //Validamos las credenciales
  if (!empty($email) && !empty($password)) {

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verficamos que la contraseña es correcta
    if ($user && password_verify($password, $user['password'])) {
      // Iniciamos la sesión y redireccionamos
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['user_email'] = $email;

      header('Location: index.php');
      exit();
    } else {
      $error = "Credenciales incorrectas. Por favor, inténtelo de nuevo.";
    }
  } else {
    $error = "Por favor, complete todos los campos.";
  }
}
?>

<!DOCTYPE html>
<html lang="es-ES">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="../css/styles.css">
  <title>Iniciar sesión</title>
</head>

<body>

  <h1>Iniciar sesión</h1>

  <?php
  // Mostramos los errores si existen.
  if (isset($error)) {
    echo "<p class='error'>$error</p>";
  }
  ?>

  <form action="login.php" method="post">
    <fieldset>
      <legend>Iniciar sesión</legend>

      <label for="user">Correo electrónico:</label><br>
      <input type="email" id="email" name="email" required><br>

      <label for="password">Contraseña:</label><br>
      <input type="password" id="password" name="password" required><br><br>

      <button type="submit">Iniciar sesión</button>
    </fieldset>
  </form>

  <p>¿No tienes una cuenta? <a href="register.php">Regístrate aquí</a>.</p>

</body>

</html>