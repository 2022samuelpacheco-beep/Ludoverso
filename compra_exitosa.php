

<?php
session_start();
?>

<!doctype html>
<html lang="es">
  <head>
    <title>Ludoverso - Acceso</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet"
      href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

    <style>
      body {
        background-color: #06070a;
        color: #f8f9fa;
        font-size: 1.05rem; /* todo un poquito más grande */
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

      /* Card casi a todo lo ancho de la pantalla */
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
        color: #ffc107;
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

      /* En pantallas pequeñas que todo sea full width y aireado */
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
      <!-- Card muy ancho, casi pantalla completa pero con margen -->
      <div class="col-12 auth-container">
        <div class="card auth-card mx-auto">
          <div class="row no-gutters">
            
            <!-- LADO IZQUIERDO: imagen + mensaje -->
            <div class="col-md-6 auth-card-left d-flex align-items-center">
              <div class="auth-card-left-content p-5 p-xl-5 text-light">
                <span class="brand-pill mb-3">Ludoverso</span>
                <h2 class="mt-1 mb-3">Tu puerta de entrada a la aventura</h2>
                <p class="mb-3">
                  accede a tu colección de manuales,
                  campañas, wargames y material de referencia para tus partidas.
                </p>
                <p class="mb-0 text-muted">
                  Organiza tus títulos, descubre nuevas historias y mantén siempre
                  lista tu mesa de juego. Ludoverso es tu biblioteca geek de confianza.
                </p>
              </div>
            </div>

            <!-- LADO DERECHO: formulario -->
            <div class="col-md-6 auth-card-right">
              <div class="p-5 p-xl-5">
                <h1 class="auth-title mb-3 text-center" >Compra Exitosa</h1>
                <p class="auth-subtitle mb-4">
                    Tu pedido fue procesado correctamente y te enviamos la factura a tu correo.
                </p>

                <?php if (isset($mensaje)) { ?>
                  <div class="alert alert-danger" role="alert">
                    <?php echo $mensaje; ?>
                  </div>
                <?php } ?>

              



                <form action="productos.php" method="get">
                    <button type="submit"
                    class="btn btn-primary btn-block btn-auth-primary mt-3">
                    Seguir comprando
                    </button>
                </form>


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