<?php
session_start();
include("administrador/config/bd.php"); // tu conexión a la BD

$mensaje = "";

if ($_POST) {

    $usuario  = $_POST['usuario']  ?? '';
    $password = $_POST['password'] ?? '';

    // 1. Validar que no vengan vacíos
    if ($usuario == "" || $password == "") {
        $mensaje = "Debes completar todos los campos.";
    } else {

        // 2. Verificar si el usuario ya existe
        $sentencia = $conexion->prepare("SELECT * FROM usuarios WHERE usuario = :usuario LIMIT 1");
        $sentencia->bindParam(':usuario', $usuario);
        $sentencia->execute();
        $usuarioBD = $sentencia->fetch(PDO::FETCH_ASSOC);

        if ($usuarioBD) {
            $mensaje = "El usuario ya existe, elige otro nombre.";
        } else {
            // 3. Insertar nuevo usuario (por ahora contraseña en texto plano)
            $insert = $conexion->prepare(
                "INSERT INTO usuarios (usuario, password) VALUES (:usuario, :password)"
            );
            $insert->bindParam(':usuario', $usuario);
            $insert->bindParam(':password', $password);
            $insert->execute();

            // 4. Loguearlo automáticamente
            $_SESSION['usuario_web']       = "ok";
            $_SESSION['nombreUsuario_web'] = $usuario;

            header("Location: index.php");
            exit;
        }
    }
}
?>

<!doctype html>
<html lang="es">
  <head>
    <title>Registro - Ludoverso</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet"
      href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

    <style>
      body {
        background-color: #06070a;
        color: #f8f9fa;
        font-size: 1.05rem;
      }

      .auth-wrapper {
        min-height: 100vh;
        padding-top: 3rem;
        padding-bottom: 3rem;
      }

      .auth-card {
        border: none;
        border-radius: 1.8rem;
        box-shadow: 0 1.4rem 3.4rem rgba(0,0,0,0.7);
        overflow: hidden;
      }

      .auth-container {
        max-width: 1400px;  /* ancho máximo grande */
      }

      .auth-card-left {
        background-image: url('img/hero_ludoverso.png');
        background-size: cover;
        background-position: center;
        position: relative;
        min-height: 350px;
      }

      .auth-card-left::before {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(0,0,0,0.9), rgba(0,0,0,0.5));
      }

      .auth-card-left-content {
        position: relative;
        z-index: 1;
      }

      .auth-card-right {
        background-color: #15171f;
      }

      .auth-title {
        font-weight: 700;
        font-size: 2rem;
      }

      .auth-subtitle {
        color: #adb5bd;
        font-size: 1.05rem;
      }

      .form-label {
        font-weight: 500;
        font-size: 1rem;
      }

      .brand-pill {
        display: inline-block;
        padding: 0.25rem 1.1rem;
        border-radius: 999px;
        background: rgba(0,0,0,0.5);
        color: #00d1ff;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
      }

      .auth-card-left-content h2 {
        font-size: 1.9rem;
        font-weight: 700;
      }

      .auth-card-left-content p {
        font-size: 1rem;
        line-height: 1.7;
      }

      .auth-card-right .form-control {
        padding: 0.9rem 1rem;
        font-size: 1rem;
      }

      .btn-auth-primary {
        padding: 0.9rem 1rem;
        font-size: 1.05rem;
        font-weight: 600;
      }

      @media (max-width: 767.98px) {
        .auth-wrapper {
          padding-top: 1.5rem;
          padding-bottom: 1.5rem;
        }
        .auth-card-left {
          min-height: 220px;
        }
        .auth-title {
          font-size: 1.6rem;
        }
      }
    </style>
  </head>
  <body>

  <div class="container-fluid auth-wrapper d-flex align-items-center">
    <div class="row w-100 justify-content-center">
      <div class="col-12 auth-container">
        <div class="card auth-card mx-auto">
          <div class="row no-gutters">
            
            <!-- LADO IZQUIERDO: imagen + mensaje -->
            <div class="col-md-6 auth-card-left d-flex align-items-center">
              <div class="auth-card-left-content p-5 p-xl-5 text-light">
                <span class="brand-pill mb-3">Registro</span>
                <h2 class="mt-1 mb-3">Crea tu cuenta en Ludoverso</h2>
                <p class="mb-3">
                  Regístrate para guardar tus libros favoritos, llevar un registro
                  de tus campañas y tener siempre a mano tu biblioteca de rol y wargames.
                </p>
                <p class="mb-0 text-muted">
                  Solo necesitas un nombre de usuario y una contraseña para empezar.
                  Más adelante podrás ampliar tu perfil si lo deseas.
                </p>
              </div>
            </div>

            <!-- LADO DERECHO: formulario -->
            <div class="col-md-6 auth-card-right">
              <div class="p-5 p-xl-5">
                <h1 class="auth-title mb-3">Crear cuenta</h1>
                <p class="auth-subtitle mb-4">
                  Completa los campos para registrarte en Ludoverso.
                </p>

                <?php if ($mensaje != "") { ?>
                  <div class="alert alert-warning" role="alert">
                    <?php echo $mensaje; ?>
                  </div>
                <?php } ?>

                <form method="POST">

                  <div class="form-group">
                    <label class="form-label">Usuario</label>
                    <input
                      type="text"
                      name="usuario"
                      class="form-control"
                      placeholder="Elige un usuario"
                      required
                    >
                  </div>

                  <div class="form-group">
                    <label class="form-label">Contraseña</label>
                    <input
                      type="password"
                      name="password"
                      class="form-control"
                      placeholder="Elige una contraseña"
                      required
                    >
                  </div>

                  <button type="submit" class="btn btn-success btn-block btn-auth-primary mt-3">
                    Crear cuenta
                  </button>

                  <a href="login.php" class="btn btn-link btn-block text-light mt-3">
                    Ya tengo cuenta, ir al login
                  </a>

                  <a href="index.php" class="btn btn-link btn-block text-secondary">
                    ← Volver al inicio
                  </a>
                </form>

              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>

  </body>
</html>
