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
      <legend>Let's log!</legend>

      <label for="user">Email adress</label><br>
      <input type="email" id="user" name="user" required><br>

      <label for="password">Password</label><br>
      <input type="password" id="password" name="password" required><br>

      <button type="submit">Login</button>

    </fieldset>
  </form>
</body>

</html>