<?php
// Iniciamos sesión solo si no hay una ya activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Nombre del usuario de la WEB (no del admin)
$nombreUsuarioWeb = $_SESSION['nombreUsuario_web'] ?? null;
?>

<!--Cabecera
--------------------------------------------------------------------------------->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ludoverso</title>

    <link rel="stylesheet" href="./css/bootstrap.min.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
    <div class="container">
        <!-- Menú izquierdo -->
        <ul class="nav navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="index.php">Ludoverso</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="index.php">Inicio</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="productos.php">Libros</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="nosotros.php">Nosotros</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="administrador/indexAdmin.php">Administrador</a>
            </li>
        </ul>

        <!-- Menú derecho: usuario / login -->
        <ul class="nav navbar-nav ml-auto">
            <?php if ($nombreUsuarioWeb) { ?>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">
                        <span class="mr-1">👤</span>
                        <?php echo htmlspecialchars($nombreUsuarioWeb); ?> — Cerrar sesión
                    </a>
                </li>
            <?php } else { ?>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">
                        <span class="mr-1">👤</span>
                        Iniciar sesión
                    </a>
                </li>
            <?php } ?>
        </ul>
    </div>
</nav>

<div class="container">
    <br><br>
    <div class="row">
