<?php
session_start();
require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
  $password = $_POST['password'];

  // Verificamos si el usuario existe
  $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
  $stmt->execute([$email]);
  $user = $stmt->fetch();

  if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    echo "Inicio de sesión existoso.";
    // Redireccionamos
    header('Location: index.php');
    exit();
  } else {
    echo "Email o contraseña incorrectos.";
  }
}
?>

<!DOCTYPE html>
<html lang="es-ES">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
</head>

<body>
  <form action="login.php" method="post">
    <fieldset>
      <legend>Iniciar sesión</legend>

      <label for="user">Email adress</label><br>
      <input type="email" id="user" name="user" required><br>

      <label for="password">Password</label><br>
      <input type="password" id="password" name="password" required><br>

      <button type="submit">Iniciar sesión</button>
    </fieldset>
  </form>
</body>

</html>