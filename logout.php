<?php
session_start();

// Cerrar solo la sesión de la WEB (no la del administrador)
unset($_SESSION['usuario_web']);
unset($_SESSION['nombreUsuario_web']);

// Si quieres borrar TODA la sesión (web + admin), usarías:
// session_unset();
// session_destroy();

header("Location: login.php");
exit;
?>
