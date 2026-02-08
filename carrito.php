<?php
session_start();

// Cabecera pública
include("template/cabecera.php");

// Conexión a la BD
include("administrador/config/bd.php");

// Inicializar carrito en sesión si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

$accion  = isset($_GET['accion']) ? $_GET['accion'] : "";
$idLibro = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// --- Lógica de carrito ---
switch ($accion) {

    case "agregar":
        if ($idLibro > 0) {
            if (isset($_SESSION['carrito'][$idLibro])) {
                $_SESSION['carrito'][$idLibro]++;   // sumamos cantidad
            } else {
                $_SESSION['carrito'][$idLibro] = 1; // primera vez
            }
        }
        break;

    case "eliminar":
        if ($idLibro > 0 && isset($_SESSION['carrito'][$idLibro])) {
            unset($_SESSION['carrito'][$idLibro]);
        }
        break;

    case "vaciar":
        $_SESSION['carrito'] = [];
        break;
}

// --- Obtener libros del carrito desde la BD ---
$librosCarrito = [];
$totalGeneral  = 0;

if (!empty($_SESSION['carrito'])) {

    $ids = array_keys($_SESSION['carrito']);

    // Construimos placeholders (?, ?, ?) según la cantidad de IDs
    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    $sentencia = $conexion->prepare(
        "SELECT * FROM libros WHERE id IN ($placeholders)"
    );

    foreach ($ids as $index => $id) {
        $sentencia->bindValue($index + 1, $id, PDO::PARAM_INT);
    }

    $sentencia->execute();
    $libros = $sentencia->fetchAll(PDO::FETCH_ASSOC);

    // Añadimos cantidad y subtotal a cada libro
    foreach ($libros as $libro) {
        $cantidad = $_SESSION['carrito'][$libro['id']];
        $subtotal = $cantidad * $libro['precio'];

        $libro['cantidad'] = $cantidad;
        $libro['subtotal'] = $subtotal;

        $librosCarrito[] = $libro;
        $totalGeneral   += $subtotal;
    }
}
?>

<!-- Contenido carrito -->
<div class="col-12 mt-4 mb-3">
    <h2>Carrito de compras</h2>
    <p class="text-muted mb-0">
        Aquí verás los libros que has ido agregando desde la biblioteca.
    </p>
</div>

<?php if (empty($librosCarrito)) { ?>

    <div class="col-12 mt-4">
        <div class="alert alert-info">
            Tu carrito está vacío por ahora.
            <a href="productos.php" class="alert-link">Ir a la biblioteca de libros</a>
        </div>
    </div>

<?php } else { ?>

    <div class="col-12">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="thead-light">
                    <tr>
                        <th width="10%">Portada</th>
                        <th width="40%">Libro</th>
                        <th width="10%" class="text-right">Precio</th>
                        <th width="10%" class="text-center">Cantidad</th>
                        <th width="15%" class="text-right">Subtotal</th>
                        <th width="15%">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($librosCarrito as $libro) { ?>
                        <tr>
                            <td>
                                <img src="img/<?php echo $libro['imagen']; ?>"
                                     alt="<?php echo htmlspecialchars($libro['nombre']); ?>"
                                     width="60">
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($libro['nombre']); ?></strong><br>
                                <small class="text-muted">
                                    <?php echo !empty($libro['categoria']) ? $libro['categoria'] : 'Sin categoría'; ?>
                                </small>
                            </td>
                            <td class="text-right">
                                S/ <?php echo number_format($libro['precio'], 2); ?>
                            </td>
                            <td class="text-center">
                                <?php echo $libro['cantidad']; ?>
                            </td>
                            <td class="text-right">
                                S/ <?php echo number_format($libro['subtotal'], 2); ?>
                            </td>
                            <td>
                                <a href="carrito.php?accion=eliminar&id=<?php echo $libro['id']; ?>"
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('¿Eliminar este libro del carrito?');">
                                    Quitar
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-right">Total</th>
                        <th class="text-right">
                            S/ <?php echo number_format($totalGeneral, 2); ?>
                        </th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="d-flex justify-content-between mb-4">
            <div>
                <a href="productos.php" class="btn btn-outline-secondary">
                    ← Seguir explorando libros
                </a>
            </div>
            <div>
                <a href="carrito.php?accion=vaciar"
                   class="btn btn-outline-danger"
                   onclick="return confirm('¿Vaciar todo el carrito?');">
                    Vaciar carrito
                </a>
                <form action="contacto.php" method="post">
    <button type="submit" class="btn btn-success">
        Proceder al pago
    </button>
</form>
            </div>
        </div>
    </div>

<?php } ?>

<?php include("template/pie.php"); ?>
