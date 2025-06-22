<?php
session_start();
// Destruir todas las variables de sesión
$_SESSION = [];
// Si se quiere borrar la cookie de sesión
if (ini_get("session.use_cookies")) {
    setcookie(session_name(), '', time() - 42000, '/');
}
// Finalmente, destruir la sesión
session_destroy();

// Redirigir al login
header('Location: ../index.php');
exit();
