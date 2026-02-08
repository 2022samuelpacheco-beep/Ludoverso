<!-- Incluir Cabecera -->
<?php include("template/cabecera.php"); ?>

<!-- Conexión a la base de datos -->
<?php 
    include("administrador/config/bd.php");

    // ====== PARÁMETROS DE BÚSQUEDA (GET) ======
    $filtroID        = isset($_GET['id'])        ? trim($_GET['id'])        : "";
    $filtroNombre    = isset($_GET['nombre'])    ? trim($_GET['nombre'])    : "";
    $filtroCategoria = isset($_GET['categoria']) ? trim($_GET['categoria']) : "";
    $filtroPrecio    = isset($_GET['precio'])    ? trim($_GET['precio'])    : "";

    // ====== PAGINACIÓN ======
    $registrosPorPagina = 8;
    $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    if ($paginaActual < 1) { $paginaActual = 1; }

    // ====== ARMAR BASE DE CONSULTA DINÁMICA ======
    $sqlBase   = " FROM libros WHERE 1=1";
    $whereSQL  = "";
    $parametros = array();

    if ($filtroID !== "") {
        $whereSQL .= " AND id = :id";
        $parametros[':id'] = $filtroID;
    }

    if ($filtroNombre !== "") {
        $whereSQL .= " AND nombre LIKE :nombre";
        $parametros[':nombre'] = "%".$filtroNombre."%";
    }

    if ($filtroCategoria !== "") {
        $whereSQL .= " AND categoria = :categoria";
        $parametros[':categoria'] = $filtroCategoria;
    }

    if ($filtroPrecio !== "") {
        $whereSQL .= " AND precio <= :precio";
        $parametros[':precio'] = $filtroPrecio;
    }

    // ====== 1) CONTAR TOTAL DE REGISTROS PARA LA PAGINACIÓN ======
    $sqlConteo = "SELECT COUNT(*) AS total" . $sqlBase . $whereSQL;
    $stmtConteo = $conexion->prepare($sqlConteo);

    foreach($parametros as $clave => $valor){
        $stmtConteo->bindValue($clave, $valor);
    }

    $stmtConteo->execute();
    $totalRegistros = (int)$stmtConteo->fetchColumn();

    // Calcular total de páginas
    $totalPaginas = ($totalRegistros > 0) 
        ? (int)ceil($totalRegistros / $registrosPorPagina) 
        : 1;

    if ($paginaActual > $totalPaginas) {
        $paginaActual = $totalPaginas;
    }

    $offset = ($paginaActual - 1) * $registrosPorPagina;

    // ====== 2) CONSULTA FINAL CON LIMIT + OFFSET ======
    $sqlLibros = "SELECT *" . $sqlBase . $whereSQL . " ORDER BY id ASC LIMIT :limit OFFSET :offset";
    $sentenciaSQL = $conexion->prepare($sqlLibros);

    // Bind de filtros
    foreach($parametros as $clave => $valor){
        $sentenciaSQL->bindValue($clave, $valor);
    }

    // Bind de paginación
    $sentenciaSQL->bindValue(':limit',  $registrosPorPagina, PDO::PARAM_INT);
    $sentenciaSQL->bindValue(':offset', $offset,             PDO::PARAM_INT);

    $sentenciaSQL->execute();
    $listaLibros = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);

    // Para armar los links de paginación conservando filtros
    $queryString = $_GET;
    unset($queryString['pagina']); // evitamos duplicar
    $queryBase = http_build_query($queryString);
?>

<style>
/* ====== ESTILOS ESPECÍFICOS PARA ESTA PÁGINA ====== */
.search-bar-ludo {
    background: #0b1120;           /* Azul oscuro tipo panel */
    border-radius: 0.9rem;
    border: 1px solid #1f2937;
    box-shadow: 0 0.9rem 2rem rgba(0,0,0,0.35);
    color: #e5e7eb;
}

.search-bar-ludo .search-title {
    font-weight: 600;
    font-size: 1.1rem;
}

.search-bar-ludo small {
    color: #9ca3af;
}

.search-chip-label {
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: #9ca3af;
    font-weight: 600;
    margin-bottom: 0.15rem;
}

.form-control-sm-ludo {
    font-size: 0.9rem;
    padding-top: 0.45rem;
    padding-bottom: 0.45rem;
    background: #020617;
    border: 1px solid #4b5563;
    color: #e5e7eb;
}

.form-control-sm-ludo::placeholder {
    color: #6b7280;
}

.form-control-sm-ludo:focus {
    border-color: #3b82f6;
    box-shadow: none;
    background: #020617;
    color: #e5e7eb;
}

.select-sm-ludo {
    background: #020617;
    border: 1px solid #4b5563;
    color: #e5e7eb;
    font-size: 0.9rem;
    padding-top: 0.45rem;
    padding-bottom: 0.45rem;
}

.select-sm-ludo:focus {
    border-color: #3b82f6;
    box-shadow: none;
    background: #020617;
    color: #e5e7eb;
}

.btn-search-primary {
    background: #3b82f6;
    border: none;
    font-size: 0.9rem;
    font-weight: 600;
}

.btn-search-primary:hover {
    background: #2563eb;
}

.btn-search-secondary {
    color: #9ca3af;
    font-size: 0.85rem;
}

.btn-search-secondary:hover {
    color: #e5e7eb;
    text-decoration: underline;
}

/* Línea divisoria entre columnas del panel */
@media (min-width: 768px) {
    .search-col-left {
        border-right: 1px solid #1f2937;
    }
}

/* Cards de libros un poco más “premium” */
.card.h-100 {
    border-radius: 0.75rem;
    border: 1px solid #1f2937;
    overflow: hidden;
    background: #020617;
    color: #e5e7eb;
}

/* Imagen completa respetando proporción, con padding */
.card-img-top {
    width: 100%;
    height: auto;
    object-fit: contain;
    background-color: #020617;
    padding: 0.75rem;
    cursor: pointer; /* Para que se vea clickeable */
}

.card .btn-primary.btn-sm {
    background: #3b82f6;
    border: none;
}

.card .btn-primary.btn-sm:hover {
    background: #2563eb;
}

.card .btn-success.btn-sm {
    background: #22c55e;
    border: none;
}

.card .btn-success.btn-sm:hover {
    background: #16a34a;
}

/* Paginación centrada y dark */
.pagination-ludo .page-link {
    background-color: #020617;
    border-color: #1f2937;
    color: #e5e7eb;
}

.pagination-ludo .page-link:hover {
    background-color: #111827;
    border-color: #374151;
    color: #fff;
}

.pagination-ludo .page-item.active .page-link {
    background-color: #3b82f6;
    border-color: #2563eb;
    color: #fff;
}

/* ====== MODAL DARK ALINEADO A LOS CARDS ====== */
.modal-content {
    background: #020617;
    color: #e5e7eb;
    border-radius: 0.75rem;
    border: 1px solid #1f2937;
}

.modal-header,
.modal-footer {
    border-color: #1f2937;
    background: #020617;
}

/* Aseguramos alineación correcta del título y la X */
.modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.modal-title {
    font-weight: 600;
}

/* Botón cerrar (X) en blanco, alineado a la derecha */
.modal-header .close {
    margin-left: auto;
    text-shadow: none;
    opacity: 0.8;
}

.modal-header .close span {
    color: #e5e7eb;
    font-size: 1.5rem;
    line-height: 1;
}

.modal-header .close:hover {
    opacity: 1;
}

.modal-body p {
    color: #e5e7eb;
}

.modal-body p.text-muted {
    color: #9ca3af !important;
}

/* Imagen dentro del modal, sin borde blanco, alineado al estilo de las cards */
.modal-body .book-modal-img {
    background-color: #020617;
    padding: 0.5rem;
    border-radius: 0.75rem;
}


/* ============================
   🔥 PREMIUM LUDOVERSO CARD FX
   ============================ */

/* Wrapper para permitir la rotación */
.card {
    perspective: 1200px;
    transform-style: preserve-3d;
    transition: transform 0.4s ease, box-shadow 0.4s ease;
}

/* Hover 3D + elevación */
.card:hover {
    transform: translateY(-12px) rotateX(4deg) rotateY(-4deg);
    box-shadow: 0 1.8rem 2.2rem rgba(0, 0, 0, 0.55),
                0 0 25px rgba(0, 0, 0, 0.35);
    border-color: #000000ff;
}

/* Imagen con efecto "iluminación" */
.card-img-top {
    transition: transform 0.55s ease, filter 0.55s ease;
}

.card:hover .card-img-top {
    transform: scale(1.05);
    filter: drop-shadow(0 0 12px rgba(194, 173, 54, 0.5));
}

/* Título efecto brillante */
.card-title {
    transition: color 0.3s ease, text-shadow 0.3s ease;
}

.card:hover .card-title {
    color: #fac460ff;
    text-shadow: 0 0 8px rgba(202, 175, 84, 0.7);
}

/* Botones más épicos al hacer hover */
.card .btn {
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}

.card .btn:hover {
    transform: scale(1.08);
    box-shadow: 0 0 10px rgba(255, 145, 0, 0.7);
}

/* Animación de entrada de cada card */
.card {
    opacity: 0;
    transform: translateY(30px);
    animation: fadeUp 0.9s ease forwards;
}

@keyframes fadeUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Animación escalonada */
.card:nth-child(1) { animation-delay: 0.1s; }
.card:nth-child(2) { animation-delay: 0.2s; }
.card:nth-child(3) { animation-delay: 0.3s; }
.card:nth-child(4) { animation-delay: 0.4s; }
.card:nth-child(5) { animation-delay: 0.5s; }
.card:nth-child(6) { animation-delay: 0.6s; }
.card:nth-child(7) { animation-delay: 0.7s; }
.card:nth-child(8) { animation-delay: 0.8s; }




/* ===========================
   🐉 DRAGÓN MIRANDO LAS CARDS
   =========================== */
.dragon-tracker {
    position: fixed;
    left: 50px;
    bottom: 20px;
    width: 180px;
    pointer-events: none;
    transition: transform 0.15s linear;
    z-index: 999;
}


/* Animación al añadir al carrito */
.add-to-cart-anim {
    animation: cartPop 0.5s ease;
}

@keyframes cartPop {
    0% { transform: scale(1); }
    40% { transform: scale(1.3); }
    100% { transform: scale(1); }
}

.custom-cart-btn {
    background: linear-gradient(135deg, #28a745, #34ce57);
    border: none;
    padding: 12px 22px;
    font-size: 1.1rem;
    font-weight: 600;
    border-radius: 12px;
    box-shadow: 0px 4px 12px rgba(40, 167, 69, 0.4);
    transition: 0.3s ease;
    color: white;
}

.custom-cart-btn:hover {
    background: linear-gradient(135deg, #34ce57, #28a745);
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0px 6px 18px rgba(40, 167, 69, 0.55);
    text-decoration: none;
}

.custom-cart-btn:active {
    transform: scale(0.97);
}

/* Estilo base del dragón */
#dragonTracker {
    width: 140px;
    transition: transform 0.4s ease, filter 0.3s ease;
    transform-style: preserve-3d;
    cursor: pointer;
}

/* Hover: efecto 3D + brillo */
#dragonTracker:hover {
    transform: rotateY(18deg) rotateX(8deg) scale(1.1);
    filter: drop-shadow(0px 6px 12px rgba(0,0,0,0.5)) 
            brightness(1.15);
}

/* Animación cuando no está en hover (flotando) */
@keyframes dragonFloat {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-8px); }
    100% { transform: translateY(0px); }
}

/* Activamos animación flotante */
#dragonTracker {
    animation: dragonFloat 3.5s ease-in-out infinite;
}


/* Animación base del dragón */
#dragonTracker {
    transition: transform 0.35s ease, filter 0.35s ease;
}

/* Clase que agranda al pasar el mouse por una card */
.dragon-hover-grow {
    transform: scale(1.35) rotateY(8deg);
    filter: brightness(1.25) drop-shadow(0px 15px 25px rgba(0,0,0,0.6));
}



</style>





<!-- Botón simple "Ir al carrito" arriba a la derecha -->
<div class="col-12 mt-3 d-flex justify-content-end">
    <a href="carrito.php" class="btn btn-success custom-cart-btn">
        🛒 Ir al carrito
    </a>
</div>


<!-- ====== BARRA DE BÚSQUEDA / FILTROS (DARK, 2 COLUMNAS) ====== -->
<div class="col-12 mt-4 mb-4">
    <div class="search-bar-ludo p-3 p-md-4">
        <div class="row align-items-center mb-3">
            <div class="col-md-8">
                <div class="search-title">Buscar en la biblioteca de Ludoverso</div>
                <small>Filtra por ID, nombre, categoría o precio máximo y encuentra rápidamente el libro que necesitas.</small>
            </div>
            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                <small>
                    Resultados: <strong><?php echo $totalRegistros; ?></strong> | 
                    Página <strong><?php echo $paginaActual; ?></strong> de <strong><?php echo $totalPaginas; ?></strong>
                </small>
            </div>
        </div>

        <form method="GET" class="mb-0">
            <div class="row">
                <!-- Columna izquierda: ID + Nombre -->
                <div class="col-md-6 mb-3 mb-md-0 search-col-left">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <div class="search-chip-label">ID</div>
                            <input type="number" 
                                   class="form-control form-control-sm-ludo" 
                                   id="id" 
                                   name="id"
                                   placeholder="Ej: 12"
                                   value="<?php echo htmlspecialchars($filtroID); ?>">
                        </div>
                        <div class="form-group col-md-8">
                            <div class="search-chip-label">Nombre contiene</div>
                            <input type="text"
                                   class="form-control form-control-sm-ludo" 
                                   id="nombre" 
                                   name="nombre"
                                   placeholder="Ej: dragón, manual, campaña..."
                                   value="<?php echo htmlspecialchars($filtroNombre); ?>">
                        </div>
                    </div>
                </div>

                <!-- Columna derecha: Categoría + Precio + Botones -->
                <div class="col-md-6">
                    <div class="form-row">
                        <div class="form-group col-md-5">
                            <div class="search-chip-label">Categoría</div>
                            <select class="form-control select-sm-ludo" id="categoria" name="categoria">
                                <option value="">Todas</option>
                                <option value="Manual de Rol" <?php echo ($filtroCategoria=="Manual de Rol") ? "selected" : ""; ?>>
                                    Manual de Rol
                                </option>
                                <option value="Campaña / Aventura" <?php echo ($filtroCategoria=="Campaña / Aventura") ? "selected" : ""; ?>>
                                    Campaña / Aventura
                                </option>
                                <option value="Wargame / Táctico" <?php echo ($filtroCategoria=="Wargame / Táctico") ? "selected" : ""; ?>>
                                    Wargame / Táctico
                                </option>
                                <option value="Guía para Director" <?php echo ($filtroCategoria=="Guía para Director") ? "selected" : ""; ?>>
                                    Guía para Director
                                </option>
                                <option value="Bestiario / Suplemento" <?php echo ($filtroCategoria=="Bestiario / Suplemento") ? "selected" : ""; ?>>
                                    Bestiario / Suplemento
                                </option>
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <div class="search-chip-label">Precio máx. (S/)</div>
                            <input type="number" step="0.01" min="0"
                                   class="form-control form-control-sm-ludo" 
                                   id="precio" 
                                   name="precio"
                                   placeholder="Ej: 80"
                                   value="<?php echo htmlspecialchars($filtroPrecio); ?>">
                        </div>

                        <div class="form-group col-md-4 d-flex align-items-end">
                            <div class="w-100 text-md-right text-left">
                                <button type="submit" class="btn btn-search-primary btn-sm mb-1">
                                    Buscar
                                </button>
                                <br>
                                <a href="productos.php" class="btn-search-secondary">
                                    Limpiar filtros
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>            
        </form>
    </div>
</div>

<!-- Mensaje si no hay resultados -->
<?php 
if (empty($listaLibros)) { ?>
    <div class="col-12 mt-4">
        <div class="alert alert-info">
            No se encontraron libros con los criterios de búsqueda seleccionados.
        </div>
    </div>
<?php
}
?>

<!-- Contenido: cards en 4 columnas -->
<?php 
foreach($listaLibros as $libro){ 

    // ID único para el modal de cada libro
    $idModal = "modalLibro".$libro['id'];
?>
<div class="col-md-3 mb-4  ">
    <div class="card h-100 ">
        <!-- Imagen que también abre el modal -->
        <img class="card-img-top "
             src="./img/<?php echo $libro['imagen']; ?>"
             alt="<?php echo $libro['nombre']; ?>"
             data-toggle="modal"
             data-target="#<?php echo $idModal; ?>">

        <div class="card-body d-flex flex-column">
            <h5 class="card-title"><?php echo $libro['nombre']; ?></h5>

            <?php if (!empty($libro['categoria'])) { ?>
                <p class="mb-1"><strong>Categoría:</strong> <?php echo $libro['categoria']; ?></p>
            <?php } ?>

            <p class="mb-1">
                <strong>Precio:</strong> S/ <?php echo number_format($libro['precio'], 2); ?>
            </p>

            <?php if (!empty($libro['descripcion'])) { ?>
                <p class="card-text small text-muted flex-grow-1">
                    <?php echo substr($libro['descripcion'], 0, 90); ?>...
                </p>
            <?php } else { ?>
                <p class="card-text small text-muted flex-grow-1">
                    Sin descripción disponible.
                </p>
            <?php } ?>

            <div class="mt-2">
                <!-- Botón Ver Más → abre modal -->
                <button type="button"
                        class="btn btn-primary btn-sm"
                        data-toggle="modal"
                        data-target="#<?php echo $idModal; ?>">
                    Ver Más
                </button>

                <!-- Botón Añadir al carrito -->
                <a href="carrito.php?accion=agregar&id=<?php echo $libro['id']; ?>"
                   class="btn btn-success btn-sm">
                    Añadir al carrito
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal con la info completa del libro -->
<div class="modal fade" id="<?php echo $idModal; ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo $idModal; ?>Label" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="<?php echo $idModal; ?>Label">
            <?php echo $libro['nombre']; ?>
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <div class="row">
            <div class="col-md-4 mb-3 mb-md-0">
                <img src="./img/<?php echo $libro['imagen']; ?>" 
                     class="img-fluid book-modal-img"
                     alt="<?php echo $libro['nombre']; ?>">
            </div>
            <div class="col-md-8">
                <?php if (!empty($libro['categoria'])) { ?>
                    <p><strong>Categoría:</strong> <?php echo $libro['categoria']; ?></p>
                <?php } ?>

                <p><strong>Precio:</strong> S/ <?php echo number_format($libro['precio'], 2); ?></p>

                <?php if (!empty($libro['descripcion'])) { ?>
                    <p><?php echo nl2br($libro['descripcion']); ?></p>
                <?php } else { ?>
                    <p class="text-muted">Este libro aún no tiene una descripción detallada.</p>
                <?php } ?>
            </div>
        </div>
      </div>

      <div class="modal-footer">
        <!-- Botón Añadir al carrito dentro del modal -->
        <a href="carrito.php?accion=agregar&id=<?php echo $libro['id']; ?>"
           class="btn btn-success">
            Añadir al carrito
        </a>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
            Cerrar
        </button>
      </div>

    </div>
  </div>
</div>

<?php } ?>

<!-- ====== PAGINACIÓN ====== -->
<?php if ($totalPaginas > 1) { ?>
    <div class="col-12 mt-4 mb-4 d-flex justify-content-center">
        <nav>
            <ul class="pagination pagination-ludo">

                <!-- Anterior -->
                <li class="page-item <?php echo ($paginaActual <= 1) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="productos.php?<?php 
                        echo $queryBase ? $queryBase.'&' : '';
                        echo 'pagina='.max(1, $paginaActual-1);
                    ?>">
                        &laquo;
                    </a>
                </li>

                <!-- Números de página -->
                <?php for($i = 1; $i <= $totalPaginas; $i++) { ?>
                    <li class="page-item <?php echo ($i == $paginaActual) ? 'active' : ''; ?>">
                        <a class="page-link" href="productos.php?<?php 
                            echo $queryBase ? $queryBase.'&' : '';
                            echo 'pagina='.$i;
                        ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                <?php } ?>

                <!-- Siguiente -->
                <li class="page-item <?php echo ($paginaActual >= $totalPaginas) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="productos.php?<?php 
                        echo $queryBase ? $queryBase.'&' : '';
                        echo 'pagina='.min($totalPaginas, $paginaActual+1);
                    ?>">
                        &raquo;
                    </a>
                </li>

            </ul>
        </nav>
    </div>
<?php } ?>

<!-- 🐉 Dragón que sigue las cards -->


<img id="dragonTracker"
     src="img/dragon.png"
     class="dragon-tracker carrito-icon">









<script>

// ===============================
// 🛒 ANIMACIÓN AL AÑADIR AL CARRITO
// ===============================
// ===============================

document.querySelectorAll('a[href*="accion=agregar"]').forEach(btn => {
    btn.addEventListener("click", (e) => {
        e.preventDefault(); // ⛔ Evita que la página cambie instantáneamente

        btn.classList.add("add-to-cart-anim");

        // Esperar la animación y luego sí enviar al carrito
        const link = btn.getAttribute("href");

        setTimeout(() => {
            window.location.href = link;
        }, 450); // coincide con la animación de 0.5s
    });
});


document.querySelectorAll('.card').forEach(card => {
    card.addEventListener("mousemove", (e) => {
        const rect = card.getBoundingClientRect();
        const x = e.clientX - rect.left - rect.width / 2;
        const y = e.clientY - rect.top - rect.height / 2;

        card.style.transform = `
            translateY(-8px) 
            rotateX(${(-y / 20)}deg) 
            rotateY(${(x / 20)}deg)
        `;
    });

    card.addEventListener("mouseleave", () => {
        card.style.transform = "";
    });
});


</script>


<script>
document.addEventListener("DOMContentLoaded", function () {

    const dragon = document.getElementById("dragonTracker");
    const cards = document.querySelectorAll(".card");

    cards.forEach(card => {
        // Cuando el mouse entra a una card
        card.addEventListener("mouseenter", () => {
            dragon.classList.add("dragon-hover-grow");
        });

        // Cuando el mouse sale de la card
        card.addEventListener("mouseleave", () => {
            dragon.classList.remove("dragon-hover-grow");
        });
    });

});
</script>





<script>
// ============================================
// 📚✨ ANIMACIÓN: EL LIBRO VUELA AL CARRITO
// ============================================

// Selecciona todos los botones "Agregar al carrito"
document.querySelectorAll('a[href*="accion=agregar"]').forEach(btn => {
    btn.addEventListener("click", (e) => {

        e.preventDefault(); // Detener la recarga inmediata

        const card = btn.closest(".card");
        const img = card.querySelector("img");

        // Clonar la imagen del libro
        const flyingImg = img.cloneNode(true);
        flyingImg.style.position = "fixed";
        flyingImg.style.zIndex = "9999";
        flyingImg.style.pointerEvents = "none";
        flyingImg.style.width = img.offsetWidth + "px";
        flyingImg.style.height = img.offsetHeight + "px";

        // Obtener posición inicial de la imagen
        const rect = img.getBoundingClientRect();
        flyingImg.style.top = rect.top + "px";
        flyingImg.style.left = rect.left + "px";

        document.body.appendChild(flyingImg);

        // Obtener posición del carrito (usa tu icono del navbar)
   const cart = document.querySelector(".carrito-icon");
const cartRect = cart.getBoundingClientRect();


        // Animación del vuelo hacia el carrito
        flyingImg.animate([
            {
                top: rect.top + "px",
                left: rect.left + "px",
                opacity: 1,
                transform: "scale(1)"
            },
            {
                top: cartRect.top + "px",
                left: cartRect.left + "px",
                opacity: 0.3,
                transform: "scale(0.2)"
            }
        ], {
            duration: 800,
            easing: "ease-in-out"
        });

        // Eliminar imagen al terminar
        setTimeout(() => {
            flyingImg.remove();
        }, 820);

        // Redirigir después de animación
        setTimeout(() => {
            window.location.href = btn.href;
        }, 500);
    });
});
</script>





<!-- Incluir Pie -->
<?php include("template/pie.php"); ?>



