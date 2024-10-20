<?php
require_once 'db_config.php';

try {
  // Creamos una conexión PDO con las credenciales
  $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);

  // Activamos el modo de error PDO para mostrar los errores en tiempo de ejecución
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {

  // Vamos a mostrar el error pero dejaremos un TODO para hacer un log en el futuro
  // TODO
  error_log("Error de conexión: " . $e->getMessage(), 0);

  // Mostramos un mensaje genérico al usuario obviando los detalles técnicos
  if (getenv('APP_ENV') === 'development') {
    die("Error de conexión: " . $e->getMessage());
  } else {
    die("Lo sentimos, no se pudo conectar a la base de datos. Inténtelo de nuevo más tarde.");
  }
}
