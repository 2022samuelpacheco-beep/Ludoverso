<?php
// administrador/seccion/usuariosAdmin.php

include("../template/cabeceraAdmin.php");
include("../config/bd.php");

// Variables
$txtID       = isset($_POST['txtID'])       ? $_POST['txtID'] : "";
$txtNombre   = isset($_POST['txtNombre'])   ? trim($_POST['txtNombre']) : "";
$txtPassword = isset($_POST['txtPassword']) ? $_POST['txtPassword'] : "";
$accion      = isset($_POST['accion'])      ? $_POST['accion'] : "";

// Lógica CRUD para usuarios_admin
switch($accion){

    case "Agregar":

        if($txtNombre != "" && $txtPassword != ""){

            $hash = password_hash($txtPassword, PASSWORD_DEFAULT);

            // OJO: ahora guardamos usuario Y nombre
            $sentenciaSQL = $conexion->prepare(
                "INSERT INTO usuarios_admin (usuario, nombre, clave) 
                 VALUES (:usuario, :nombre, :clave)"
            );
            $sentenciaSQL->bindParam(':usuario', $txtNombre); // login
            $sentenciaSQL->bindParam(':nombre',  $txtNombre); // nombre para mostrar (mismo valor)
            $sentenciaSQL->bindParam(':clave',   $hash);
            $sentenciaSQL->execute();
        }

        // Limpiamos campos
        $txtNombre   = "";
        $txtPassword = "";
        break;

    case "Seleccionar":

        $sentenciaSQL = $conexion->prepare(
            "SELECT * FROM usuarios_admin WHERE id = :id"
        );
        $sentenciaSQL->bindParam(':id', $txtID);
        $sentenciaSQL->execute();
        $usuario = $sentenciaSQL->fetch(PDO::FETCH_LAZY);

        if($usuario){
            // Cargamos el login en el campo de texto
            $txtNombre = $usuario['usuario'];
        }
        break;

    case "Modificar":

        if($txtID != ""){

            // Actualizar usuario y nombre (mantenerlos iguales)
            $sentenciaSQL = $conexion->prepare(
                "UPDATE usuarios_admin 
                 SET usuario = :usuario,
                     nombre  = :nombre
                 WHERE id   = :id"
            );
            $sentenciaSQL->bindParam(':usuario', $txtNombre);
            $sentenciaSQL->bindParam(':nombre',  $txtNombre);
            $sentenciaSQL->bindParam(':id',      $txtID);
            $sentenciaSQL->execute();

            // Si se envía una nueva contraseña, actualizarla
            if($txtPassword != ""){
                $hash = password_hash($txtPassword, PASSWORD_DEFAULT);
                $sentenciaSQL = $conexion->prepare(
                    "UPDATE usuarios_admin SET clave = :clave WHERE id = :id"
                );
                $sentenciaSQL->bindParam(':clave', $hash);
                $sentenciaSQL->bindParam(':id',    $txtID);
                $sentenciaSQL->execute();
            }
        }

        // Reset
        $txtID       = "";
        $txtNombre   = "";
        $txtPassword = "";
        break;

    case "Borrar":

        if($txtID != ""){
            $sentenciaSQL = $conexion->prepare(
                "DELETE FROM usuarios_admin WHERE id = :id"
            );
            $sentenciaSQL->bindParam(':id', $txtID);
            $sentenciaSQL->execute();
        }

        // Reset
        $txtID       = "";
        $txtNombre   = "";
        $txtPassword = "";
        break;

    case "Cancelar":

        $txtID       = "";
        $txtNombre   = "";
        $txtPassword = "";
        break;
}

// Listado de todos los usuarios admin
$sentenciaSQL = $conexion->prepare("SELECT * FROM usuarios_admin ORDER BY id ASC");
$sentenciaSQL->execute();
$listaUsuarios = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="container">
    <br>
    <h3>Gestión de usuarios del Administrador</h3>
    <p class="text-muted">
        Aquí puedes crear y administrar las cuentas que tienen acceso al panel de administración.
    </p>

    <div class="row">
        <!-- Formulario -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    Datos de usuario admin
                </div>
                <div class="card-body">
                    <form method="POST">

                        <div class="form-group">
                            <label for="txtID">ID</label>
                            <input type="text"
                                   class="form-control"
                                   name="txtID"
                                   id="txtID"
                                   value="<?php echo $txtID; ?>"
                                   readonly>
                        </div>

                        <div class="form-group">
                            <label for="txtNombre">Nombre (login)</label>
                            <input type="text"
                                   class="form-control"
                                   name="txtNombre"
                                   id="txtNombre"
                                   value="<?php echo htmlspecialchars($txtNombre); ?>"
                                   placeholder="Ej: admin"
                                   required>
                        </div>

                        <div class="form-group">
                            <label for="txtPassword">
                                Contraseña <?php echo ($txtID ? "(dejar vacío si no la cambias)" : ""); ?>
                            </label>
                            <input type="password"
                                   class="form-control"
                                   name="txtPassword"
                                   id="txtPassword"
                                   placeholder="********">
                        </div>

                        <div class="btn-group" role="group">
                            <button type="submit"
                                    name="accion"
                                    value="Agregar"
                                    class="btn btn-success"
                                    <?php echo ($txtID!="") ? "disabled" : ""; ?>>
                                Agregar
                            </button>
                            <button type="submit"
                                    name="accion"
                                    value="Modificar"
                                    class="btn btn-warning"
                                    <?php echo ($txtID=="") ? "disabled" : ""; ?>>
                                Modificar
                            </button>
                            <button type="submit"
                                    name="accion"
                                    value="Cancelar"
                                    class="btn btn-secondary"
                                    <?php echo ($txtID=="") ? "disabled" : ""; ?>>
                                Cancelar
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <!-- Tabla de usuarios -->
        <div class="col-md-8">
            <div class="table-responsive mt-3 mt-md-0">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="10%">ID</th>
                            <th width="40%">Usuario (login)</th>
                            <th width="30%">Creado</th>
                            <th width="20%">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($listaUsuarios as $usuario) { ?>
                        <tr>
                            <td><?php echo $usuario['id']; ?></td>
                            <td><?php echo htmlspecialchars($usuario['usuario']); ?></td>
                            <td><?php echo $usuario['creado_en']; ?></td>
                            <td>
                                <form method="POST" class="mb-0">
                                    <input type="hidden" name="txtID"     value="<?php echo $usuario['id']; ?>">
                                    <input type="hidden" name="txtNombre" value="<?php echo htmlspecialchars($usuario['usuario']); ?>">

                                    <button type="submit"
                                            name="accion"
                                            value="Seleccionar"
                                            class="btn btn-primary btn-sm">
                                        Editar
                                    </button>

                                    <button type="submit"
                                            name="accion"
                                            value="Borrar"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('¿Seguro de borrar este usuario admin?');">
                                        Borrar
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php } ?>
                        <?php if(empty($listaUsuarios)) { ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted">
                                Aún no hay usuarios de administrador registrados.
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<?php include("../template/pieAdmin.php"); ?>
