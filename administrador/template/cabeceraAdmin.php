<?php 
session_start();
if(!isset($_SESSION['usuario'])){
    header("Location:../indexAdmin.php");
} else {
    if($_SESSION['usuario']=="ok"){
        $nombreUsuario = $_SESSION['nombreUsuario'];
    }
}
?>

<!-- Cabecera Administrador
--------------------------------------------------------------------------------->
<!doctype html>
<html lang="es">
  <head>
    <title>Administrador - Ludoverso</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet"
          href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
          crossorigin="anonymous">
  </head>
  <body>

  <?php $url = "http://".$_SERVER['HTTP_HOST']."/sitioweb1"; ?>

  <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <!-- Título / brand -->
      <a class="navbar-brand font-weight-bold" href="<?php echo $url; ?>/administrador/inicioAdmin.php">
          Administrador del Sitio Web
      </a>

      <!-- Botón para colapsar en móvil -->
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#adminNavbar"
              aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="adminNavbar">
          <!-- Menú izquierdo -->
          <div class="navbar-nav mr-auto">
              <a class="nav-item nav-link" href="<?php echo $url; ?>/administrador/inicioAdmin.php">Inicio</a>
              <a class="nav-item nav-link" href="<?php echo $url; ?>/administrador/seccion/productosAdmin.php">Libros</a>
              <a class="nav-item nav-link" href="<?php echo $url; ?>/administrador/seccion/usuariosAdmin.php">Crear Usuarios</a>
              <a class="nav-item nav-link" href="<?php echo $url; ?>/index.php">Ver Sitio</a>
          </div>

          <!-- Botón Cerrar alineado al otro lado -->
          <div class="navbar-nav ml-auto">
              <span class="navbar-text mr-2">
                  <?php echo isset($nombreUsuario) ? "Conectado como: ".htmlspecialchars($nombreUsuario) : ""; ?>
              </span>
              <a class="nav-item nav-link text-danger" href="<?php echo $url; ?>/administrador/seccion/cerrarAdmin.php">
                  Cerrar
              </a>
          </div>
      </div>
  </nav>

  <div class="container">
      <div class="row">
