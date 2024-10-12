<?php
require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
  $password = $_POST['password'];
}
?>

<!DOCTYPE html>
<html lang="es-ES">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registro</title>
</head>

<body>
  <form action="register.php" method="post">
    <fieldset>
      <legend>Let's sign up</legend>

      <label for="user">Email adress</label><br>
      <input type="email" id="user" name="user" required><br>

      <label for="password">Password</label><br>
      <input type="password" id="password" name="password" required><br>

      <button type="submit">Sign up</button>

    </fieldset>
  </form>
</body>

</html>