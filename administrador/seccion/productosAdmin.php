<!--Incluir Cabecera Administrador
--------------------------------------------------------------------------------->
<?php include("../template/cabeceraAdmin.php"); ?>

<?php 

$txtID          = (isset($_POST['txtID']))         ? $_POST['txtID']         : "";
$txtNombre      = (isset($_POST['txtNombre']))     ? $_POST['txtNombre']     : "";
$txtImagen      = (isset($_FILES['txtImagen']['name'])) ? $_FILES['txtImagen']['name'] : "";
$txtDescripcion = (isset($_POST['txtDescripcion']))? $_POST['txtDescripcion']: "";
$txtPrecio      = (isset($_POST['txtPrecio']))     ? $_POST['txtPrecio']     : "";
$txtCategoria   = (isset($_POST['txtCategoria']))  ? $_POST['txtCategoria']  : "";

$accion         = (isset($_POST['accion']))        ? $_POST['accion']        : "";

/*--Incluir Cabecera Conexion a Base de Datos
---------------------------------------------------------------------------------*/
include("../config/bd.php");


/*--Insertar / Modificar / Borrar elementos en la Base de Datos
---------------------------------------------------------------------------------*/
switch($accion){

    case "Agregar":

        // INSERT con los nuevos campos
        $sentenciaSQL = $conexion->prepare(
            "INSERT INTO libros (nombre, imagen, descripcion, precio, categoria) 
             VALUES (:nombre, :imagen, :descripcion, :precio, :categoria);"
        );
        $sentenciaSQL->bindParam(':nombre',      $txtNombre);
        $sentenciaSQL->bindParam(':descripcion', $txtDescripcion);
        $sentenciaSQL->bindParam(':precio',      $txtPrecio);
        $sentenciaSQL->bindParam(':categoria',   $txtCategoria);

        // Manejo de imagen
        $fecha = new DateTime();
        $nombreArchivo = ($txtImagen != "") 
            ? $fecha->getTimestamp() . "_" . $_FILES["txtImagen"]["name"] 
            : "imagen.jpg";
        
        $tmpImagen = $_FILES["txtImagen"]["tmp_name"];

        if($tmpImagen != ""){
            move_uploaded_file($tmpImagen, "../../img/".$nombreArchivo);
        }

        $sentenciaSQL->bindParam(':imagen', $nombreArchivo);
        $sentenciaSQL->execute();
        break;



    case "Modificar":

        // Actualizar nombre, descripcion, precio y categoria
        $sentenciaSQL = $conexion->prepare(
            "UPDATE libros 
             SET nombre = :nombre,
                 descripcion = :descripcion,
                 precio = :precio,
                 categoria = :categoria
             WHERE id = :id"
        );
        $sentenciaSQL->bindParam(':nombre',      $txtNombre);
        $sentenciaSQL->bindParam(':descripcion', $txtDescripcion);
        $sentenciaSQL->bindParam(':precio',      $txtPrecio);
        $sentenciaSQL->bindParam(':categoria',   $txtCategoria);
        $sentenciaSQL->bindParam(':id',          $txtID);
        $sentenciaSQL->execute();

        // Si se envía una nueva imagen, la reemplazamos
        if($txtImagen != ""){

            $fecha = new DateTime();
            $nombreArchivo = ($txtImagen != "") 
                ? $fecha->getTimestamp() . "_" . $_FILES["txtImagen"]["name"] 
                : "imagen.jpg";
            $tmpImagen = $_FILES["txtImagen"]["tmp_name"];

            if($tmpImagen != ""){
                move_uploaded_file($tmpImagen, "../../img/".$nombreArchivo);
            }

            // Borrar imagen anterior si no es la genérica
            $sentenciaSQL = $conexion->prepare("SELECT imagen FROM libros WHERE id = :id");
            $sentenciaSQL->bindParam(':id', $txtID);
            $sentenciaSQL->execute();
            $libro = $sentenciaSQL->fetch(PDO::FETCH_LAZY);

            if(isset($libro["imagen"]) && ($libro["imagen"] != "imagen.jpg")){
                if(file_exists("../../img/".$libro["imagen"])){
                    unlink("../../img/".$libro["imagen"]);
                }
            }

            // Actualizar el nombre de la nueva imagen
            $sentenciaSQL = $conexion->prepare(
                "UPDATE libros SET imagen = :imagen WHERE id = :id"
            );
            $sentenciaSQL->bindParam(':imagen', $nombreArchivo);
            $sentenciaSQL->bindParam(':id',     $txtID);
            $sentenciaSQL->execute();
        }

        header("location:productosAdmin.php");
        break;



    case "Cancelar":

        header("location:productosAdmin.php");
        break;



    case "Seleccionar":

        $sentenciaSQL = $conexion->prepare("SELECT * FROM libros WHERE id = :id");
        $sentenciaSQL->bindParam(':id', $txtID);
        $sentenciaSQL->execute();
        $libro = $sentenciaSQL->fetch(PDO::FETCH_LAZY);

        $txtNombre      = $libro['nombre'];
        $txtImagen      = $libro['imagen'];
        $txtDescripcion = $libro['descripcion'];
        $txtPrecio      = $libro['precio'];
        $txtCategoria   = $libro['categoria'];

        break;



    case "Borrar":

        // Borrar imagen física si no es la genérica
        $sentenciaSQL = $conexion->prepare("SELECT imagen FROM libros WHERE id = :id");
        $sentenciaSQL->bindParam(':id', $txtID);
        $sentenciaSQL->execute();
        $libro = $sentenciaSQL->fetch(PDO::FETCH_LAZY);

        if(isset($libro["imagen"]) && ($libro["imagen"] != "imagen.jpg")){
            if(file_exists("../../img/".$libro["imagen"])){
                unlink("../../img/".$libro["imagen"]);
            }
        }

        // Borrar registro
        $sentenciaSQL = $conexion->prepare("DELETE FROM libros WHERE id = :id");
        $sentenciaSQL->bindParam(':id', $txtID);
        $sentenciaSQL->execute();

        header("location:productosAdmin.php");
        break;
}


/* ====== PAGINACIÓN (8 REGISTROS) ====== */

// cuántos registros por página
$registrosPorPagina = 8;

// página actual (GET)
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if($paginaActual < 1){ $paginaActual = 1; }

// 1) Contar total de registros
$sentenciaSQL = $conexion->prepare("SELECT COUNT(*) FROM libros");
$sentenciaSQL->execute();
$totalRegistros = (int)$sentenciaSQL->fetchColumn();

// total de páginas
$totalPaginas = ($totalRegistros > 0) ? (int)ceil($totalRegistros / $registrosPorPagina) : 1;
if($paginaActual > $totalPaginas){ $paginaActual = $totalPaginas; }

// calcular offset
$offset = ($paginaActual - 1) * $registrosPorPagina;

// 2) Traer sólo los libros de la página actual
$sentenciaSQL = $conexion->prepare(
    "SELECT * FROM libros ORDER BY id ASC LIMIT :limit OFFSET :offset"
);
$sentenciaSQL->bindValue(':limit',  $registrosPorPagina, PDO::PARAM_INT);
$sentenciaSQL->bindValue(':offset', $offset,             PDO::PARAM_INT);
$sentenciaSQL->execute();
$listaLibros = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);

?>


<!--Contenedor de elementos
--------------------------------------------------------------------------------->
<div class="col-md-5">
    <br>
    <div class="card card">

        <div class="card-header">
            Datos de Libro
        </div>

        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                
                <div class="form-group">
                    <label for="txtID">ID</label>
                    <input type="text" required readonly 
                        class="form-control" 
                        value="<?php echo $txtID; ?>" 
                        name="txtID" id="txtID" placeholder="ID">
                </div>

                <div class="form-group">
                    <label for="txtNombre">Nombre</label>
                    <input type="text" required 
                        class="form-control" 
                        value="<?php echo $txtNombre; ?>" 
                        name="txtNombre" id="txtNombre" 
                        placeholder="Nombre del Libro">
                </div>

                <!-- CATEGORÍA COMO DESPLEGABLE -->
                <div class="form-group">
                    <label for="txtCategoria">Categoría</label>
                    <select class="form-control"
                            name="txtCategoria"
                            id="txtCategoria">
                        <option value="">Seleccione una categoría</option>
                        <option value="Manual de Rol" <?php echo ($txtCategoria=="Manual de Rol") ? "selected" : ""; ?>>
                            Manual de Rol
                        </option>
                        <option value="Campaña / Aventura" <?php echo ($txtCategoria=="Campaña / Aventura") ? "selected" : ""; ?>>
                            Campaña / Aventura
                        </option>
                        <option value="Wargame / Táctico" <?php echo ($txtCategoria=="Wargame / Táctico") ? "selected" : ""; ?>>
                            Wargame / Táctico
                        </option>
                        <option value="Guía para Director" <?php echo ($txtCategoria=="Guía para Director") ? "selected" : ""; ?>>
                            Guía para Director
                        </option>
                        <option value="Bestiario / Suplemento" <?php echo ($txtCategoria=="Bestiario / Suplemento") ? "selected" : ""; ?>>
                            Bestiario / Suplemento
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="txtPrecio">Precio (USD)</label>
                    <input type="number" step="0.01" min="0"
                        class="form-control"
                        value="<?php echo $txtPrecio; ?>"
                        name="txtPrecio" id="txtPrecio"
                        placeholder="Ej: 39.90">
                </div>

                <div class="form-group">
                    <label for="txtDescripcion">Descripción</label>
                    <textarea class="form-control"
                        name="txtDescripcion" id="txtDescripcion"
                        rows="4"
                        placeholder="Breve descripción del contenido del libro"><?php echo $txtDescripcion; ?></textarea>
                </div>

                <div class="form-group">
                    <label for="txtImagen">Imagen</label>
                    <br>
                    <?php if($txtImagen != ""){ ?>
                        <img src="../../img/<?php echo $txtImagen; ?>" 
                             width="150"
                             alt=""
                             class="img-thumbnail rounded d-block mx-auto mb-2">
                    <?php } ?>

                    <input type="file" class="form-control" 
                        name="txtImagen" id="txtImagen">
                </div>

                <br>
                <div class="btn-group" role="group">
                    <button type="submit" name="accion" 
                        <?php echo ($accion=="Seleccionar") ? "disabled" : ""; ?>
                        value="Agregar" class="btn btn-success">
                        Agregar
                    </button>
                    <button type="submit" name="accion" 
                        <?php echo ($accion!="Seleccionar") ? "disabled" : ""; ?>
                        value="Modificar" class="btn btn-warning">
                        Modificar
                    </button>
                    <button type="submit" name="accion" 
                        <?php echo ($accion!="Seleccionar") ? "disabled" : ""; ?>
                        value="Cancelar" class="btn btn-secondary">
                        Cancelar
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<div class="col-md-7">
    <br>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="15%">Nombre</th>
                <th width="15%">Imagen</th>
                <th width="10%">Precio</th>
                <th width="15%">Categoría</th>
                <th width="25%">Descripción</th>
                <th width="15%">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($listaLibros as $libro) { ?>
            <tr>
                <td><?php echo $libro['id']; ?></td>
                <td><?php echo $libro['nombre']; ?></td>

                <td>
                    <img src="../../img/<?php echo $libro['imagen']; ?>" 
                         width="80" alt="">
                </td>

                <td>
                    $<?php echo $libro['precio']; ?>
                </td>

                <td>
                    <?php echo $libro['categoria']; ?>
                </td>

                <td>
                    <?php
                        if(!empty($libro['descripcion'])){
                            echo nl2br(substr($libro['descripcion'], 0, 120)).(strlen($libro['descripcion'])>120 ? "..." : "");
                        } else {
                            echo "<span class='text-muted'>Sin descripción</span>";
                        }
                    ?>
                </td>

                <td>
                    <form method="post" class="mb-0">
                        <input type="hidden" name="txtID" id="txtID" 
                               value="<?php echo $libro['id']; ?>"/>

                        <input type="submit" name="accion" 
                               value="Seleccionar" 
                               class="btn btn-primary btn-sm"/>

                        <input type="submit" name="accion" 
                               value="Borrar" 
                               class="btn btn-danger btn-sm"/>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- Paginación -->
    <?php if ($totalPaginas > 1) { ?>
        <nav aria-label="Paginación admin libros">
            <ul class="pagination justify-content-center">
                <!-- Anterior -->
                <li class="page-item <?php echo ($paginaActual <= 1) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="productosAdmin.php?pagina=<?php echo max(1, $paginaActual - 1); ?>">
                        &laquo;
                    </a>
                </li>

                <!-- Números -->
                <?php for($i=1; $i <= $totalPaginas; $i++){ ?>
                    <li class="page-item <?php echo ($i == $paginaActual) ? 'active' : ''; ?>">
                        <a class="page-link" href="productosAdmin.php?pagina=<?php echo $i; ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                <?php } ?>

                <!-- Siguiente -->
                <li class="page-item <?php echo ($paginaActual >= $totalPaginas) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="productosAdmin.php?pagina=<?php echo min($totalPaginas, $paginaActual + 1); ?>">
                        &raquo;
                    </a>
                </li>
            </ul>
        </nav>
    <?php } ?>

</div>

<!--Incluir Pie Administrador
--------------------------------------------------------------------------------->
<?php include("../template/pieAdmin.php"); ?>
