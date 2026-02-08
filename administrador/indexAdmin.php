<?php
session_start();

// Conexión a la BD (ruta correcta desde /administrador/)
include("config/bd.php");

$mensaje     = "";
$usuarioForm = "";

if ($_POST) {

    // Campos que vienen del formulario
    $usuarioForm     = isset($_POST['usuario'])     ? trim($_POST['usuario'])     : "";
    $contraseniaForm = isset($_POST['contrasenia']) ? trim($_POST['contrasenia']) : "";

    if ($usuarioForm === "" || $contraseniaForm === "") {

        $mensaje = "Debes ingresar usuario y contraseña.";

    } else {

        // Buscar el usuario en la tabla usuarios_admin
        $sentencia = $conexion->prepare(
            "SELECT * FROM usuarios_admin WHERE usuario = :usuario LIMIT 1"
        );
        $sentencia->bindParam(":usuario", $usuarioForm);
        $sentencia->execute();

        $usuarioBD = $sentencia->fetch(PDO::FETCH_ASSOC);

        if (!$usuarioBD) {

            // No existe el usuario
            $mensaje = "El usuario no existe.";

        } else {

            // Verificamos la contraseña hasheada en la columna 'clave'
            if (password_verify($contraseniaForm, $usuarioBD['clave'])) {

                // Login correcto
                $_SESSION['usuario']       = "ok";
                $_SESSION['nombreUsuario'] = $usuarioBD['nombre'];   // o $usuarioBD['usuario']

                // Redirigir al panel admin
                header('Location:inicioAdmin.php');
                exit;

            } else {
                $mensaje = "La contraseña es incorrecta.";
            }
        }
    }
}
?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <title>Administrador del Sitio Web - Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Mismo Bootstrap que en cabeceraAdmin.php -->
    <link rel="stylesheet"
          href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
          crossorigin="anonymous">
  </head>
  <body>

    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-4">
          <br><br>

          <div class="card">
            <div class="card-header">
              Acceso Administrador
            </div>
            <div class="card-body">

              <?php if (!empty($mensaje)) { ?>
                <div class="alert alert-danger" role="alert">
                  <?php echo $mensaje; ?>
                </div>
              <?php } ?>

              <form method="POST">
                <div class="form-group">
                  <label for="usuario">Usuario</label>
                  <input type="text"
                         class="form-control"
                         id="usuario"
                         name="usuario"
                         value="<?php echo htmlspecialchars($usuarioForm); ?>"
                         placeholder="Ej: Admin">
                </div>

                <div class="form-group">
                  <label for="contrasenia">Contraseña</label>
                  <input type="password"
                         class="form-control"
                         id="contrasenia"
                         name="contrasenia"
                         placeholder="Tu contraseña">
                </div>

                <button type="submit" class="btn btn-primary">
                  Ingresar
                </button>
                <a href="../index.php" class="btn btn-secondary">
                  Volver al sitio
                </a>
              </form>

            </div>
          </div>

        </div>
      </div>
    </div>

  </body>
</html>
