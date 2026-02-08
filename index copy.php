<?php
session_start();

// Validar que el usuario haya iniciado sesión
if (!isset($_SESSION['usuario_web']) || $_SESSION['usuario_web'] != "ok") {
    header("Location: login.php");
    exit;
}

// Nombre del usuario que viene del login
$nombreUsuario = $_SESSION['nombreUsuario_web'] ?? "Usuario";
?>

<!--Incluir Cabecera
--------------------------------------------------------------------------------->
<?php include("template/cabecera.php"); ?>

<!--Contenedor de elementos
--------------------------------------------------------------------------------->
<div class="jumbotron">
    <h1 class="display-3">
        Bienvenidos a Ludoverso, <?php echo htmlspecialchars($nombreUsuario); ?>
    </h1>

    <p class="lead">Consulta libros especializados en cultura Geek</p>
    <hr class="my-2">
    <p>Más Información</p>
    <p class="lead">
        <!-- Cambia este enlace por la ruta real de tu listado de libros -->
        <a class="btn btn-primary btn-lg" href="productos.php" role="button">
            Almacén de Libros
        </a>
    </p>
</div>

<!--Incluir Pie
--------------------------------------------------------------------------------->
<?php include("template/pie.php"); ?>
