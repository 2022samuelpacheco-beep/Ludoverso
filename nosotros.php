<!-- Incluir Cabecera -->
<?php include("template/cabecera.php"); ?>

<style>
/* ====== ESTILOS SOLO PARA ESTA PÁGINA ====== */

.page-nosotros-ludo {
    background: #020617;
    color: #e5e7eb;
}

/* Sección hero */
.section-hero-nos {
    background: radial-gradient(circle at top, #111827 0, #020617 55%);
    color: #e5e7eb;
}

.section-title {
    font-weight: 700;
    margin-bottom: 0.75rem;
}

.section-subtitle {
    color: #9ca3af;
}

.badge-ludo {
    background: #fbbf24;
    color: #111827;
    font-weight: 600;
}

/* Cards oscuras */
.card-ludoverso {
    border-radius: 0.75rem;
    border: 1px solid #1f2937;
    overflow: hidden;
    background: #020617;
    color: #e5e7eb;
    box-shadow: 0 0.85rem 1.8rem rgba(0, 0, 0, 0.55);
    height: 100%;
}

/* Por si se usan imágenes dentro de la card */
.card-ludoverso img {
    width: 100%;
    height: 220px;
    object-fit: cover;
}

/* Imagen amplia para los 3 bloques de características */
.feature-img-nos {
    width: 100%;
    height: 220px;          /* amplia en el card */
    object-fit: cover;      /* recorta sin deformar */
    display: block;
}

/* Bloque oscuro final */
.bg-ludo-dark {
    background-color: #020617;
    color: #f9fafb;
    border-top: 1px solid #1f2937;
}

.bg-ludo-dark p {
    color: #d1d5db;
}

/* Contenedor del video en el hero: GRANDE, 16:9 */
.video-hero-wrapper {
    position: relative;
    width: 100%;
    padding-bottom: 56.25%; /* 16:9 */
    height: 0;
    overflow: hidden;
    border-radius: 0.75rem;
    border: 1px solid #1f2937;
    box-shadow: 0 0.85rem 1.8rem rgba(0, 0, 0, 0.55);
    background: #000;
}

.video-hero-wrapper iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border: 0;
}

/* Texto bajo el video */
.video-caption {
    color: #9ca3af;
    font-size: 0.9rem;
    margin-top: 0.5rem;
}

/* =========================
   ✨ Estilo suave y elegante
   ========================= */

/* Títulos con entrada suave */
.section-title,
.section-subtitle {
    opacity: 0;
    animation: fadeSoft 0.8s ease-out forwards;
}

@keyframes fadeSoft {
    from { opacity: 0; transform: translateY(10px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* Cards con hover suave (sin levantar demasiado) */
.card-ludoverso {
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}

.card-ludoverso:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.7rem 1.4rem rgba(0, 0, 0, 0.25);
}

/* Imágenes sin parallax, solo un ligero zoom */
.card-ludoverso img {
    transition: transform 0.4s ease;
}

.card-ludoverso:hover img {
    transform: scale(1.03);
}

/* Títulos de card: glow MUY suave */
.card-ludoverso:hover .card-title {
    color: #facc15;
    text-shadow: 0 0 6px rgba(250, 204, 21, 0.35);
}

/* Video con efecto suave */
.video-hero-wrapper {
    transition: transform 0.3s ease;
}

.video-hero-wrapper:hover {
    transform: scale(1.01);
}

/* Hover elegante en la lista */
.bg-ludo-dark li {
    transition: color 0.2s ease;
}

.bg-ludo-dark li:hover {
    color: #facc15;
}

/* Fade suave para todo al cargar */
.fade-soft {
    opacity: 0;
    animation: fadeSoft 1s ease-out forwards;
}


/* Reveal animations */
  .reveal { opacity:0; transform:translateY(18px); transition: all .8s cubic-bezier(.2,.9,.2,1); }
  .reveal.visible { opacity:1; transform:none; }

  /* Floating */
  .floaty { animation: floaty 6s ease-in-out infinite; }
  @keyframes floaty { 0%{transform:translateY(0)}50%{transform:translateY(-8px)}100%{transform:translateY(0)} }

  /* Responsive */
  @media (max-width:900px){
    .hero-grid{ flex-direction:column; gap:18px; padding:0 10px; }
    .hero-right{ text-align:left; width:auto; }
    .hero-ludoverso { margin-left:8px; margin-right:8px; border-radius:12px; }
  }


</style>

<div class="page-nosotros-ludo">

    <!-- HERO NOSOTROS -->
    <section class="py-5 section-hero-nos">
        <div class="container ">
            <div class="row align-items-center">

                <!-- Texto de la izquierda -->
                <div class="col-md-6 mb-4 mb-md-0 card-ludoverso floaty">
                    <span class="badge badge-ludo mb-2">Sobre Ludoverso</span>
                    <h1 class="section-title display-5">Somos Ludoverso</h1>
                    <p class="lead section-subtitle">
                        Un espacio dedicado a los libros de rol, campañas, wargames y
                        todo lo que necesitas para vivir grandes historias en la mesa.
                    </p>
                    <p>
                        Nuestro objetivo es reunir en un solo lugar manuales, reglamentos
                        y módulos listos para jugar, pensados tanto para jugadores que
                        están empezando como para directores de juego con años de
                        experiencia.
                    </p>
                </div>

                <!-- Video grande a la derecha -->
                <div class="col-md-6">
                    <div class="video-hero-wrapper ">
                        <iframe 
                            src="https://www.youtube.com/embed/oCHR4ZjxIks"
                            title="Presentación de Ludoverso"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen>
                        </iframe>
                    </div>
                    <p class="video-caption">
                        Video de presentación de Ludoverso — conoce nuestra biblioteca digital de rol y wargames.
                    </p>
                </div>

            </div>
        </div>
    </section>

    <!-- QUÉ HACEMOS / PARA QUIÉN / CÓMO TRABAJAMOS -->
    <section class="py-5" style="background:#020617;">
        <div class="container ">

            <div class="row text-center mb-4 card-ludoverso floaty">
                <div class="col">
                    <h2 class="section-title">Lo que encontrarás en Ludoverso</h2>
                    <p class="section-subtitle">
                        Más que un catálogo: una herramienta para preparar tus sesiones
                        con rapidez y calidad.
                    </p>
                </div>
            </div>

            <div class="row">

                <!-- Card 1: Manuales y reglamentos -->
                <div class="col-md-4 mb-4">
                    <div class="card-ludoverso h-100 card-ludoverso floaty">
                        <!-- Imagen amplia en el card -->
                        <img src="img/feature_manuales.png"
                             alt="Manuales y reglamentos"
                             class="feature-img-nos">
                        <div class="card-body">
                            <h5 class="card-title">Manuales y reglamentos</h5>
                            <p class="card-text">
                                Libros para aprender a jugar, dirigir campañas, crear
                                personajes y entender las reglas de diferentes sistemas
                                de rol y wargame.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Campañas y aventuras -->
                <div class="col-md-4 mb-4">
                    <div class="card-ludoverso h-100 card-ludoverso floaty">
                        <!-- Imagen amplia en el card -->
                        <img src="img/feature_campanas.png"
                             alt="Campañas y aventuras"
                             class="feature-img-nos">
                        <div class="card-body">
                            <h5 class="card-title">Campañas y aventuras</h5>
                            <p class="card-text">
                                Módulos completos con tramas, PNJ, mapas e ideas de misión
                                listos para llevar directamente a tu mesa sin partir de cero.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Wargames y táctica -->
                <div class="col-md-4 mb-4">
                    <div class="card-ludoverso h-100 card-ludoverso floaty">
                        <!-- Imagen amplia en el card -->
                        <img src="img/feature_wargames.png"
                             alt="Wargames y táctica"
                             class="feature-img-nos">
                        <div class="card-body">
                            <h5 class="card-title">Wargames y táctica</h5>
                            <p class="card-text">
                                Reglamentos de escaramuzas y batallas, ideales para
                                quienes disfrutan del componente táctico y estratégico
                                de los juegos de mesa.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- VISIÓN / CÓMO USAR LUDOVERSO -->
    <section class="py-5 bg-ludo-dark">
        <div class="container card-ludoverso floaty">
            <div class="row">

                <div class="col-md-6 mb-4 mb-md-0">
                    <h2 class="section-title">Nuestra visión</h2>
                    <p>
                        Creemos que las historias compartidas alrededor de una mesa
                        son una de las mejores formas de aprender, imaginar y
                        conectar con otras personas.
                    </p>
                    <p>
                        Por eso Ludoverso busca convertirse en un punto de referencia
                        para quienes aman el rol, los wargames y la narrativa
                        interactiva: una biblioteca viva que pueda seguir creciendo
                        con nuevas colecciones y contenidos.
                    </p>
                </div>

                <div class="col-md-6">
                    <h2 class="section-title">Cómo aprovechar Ludoverso</h2>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <strong>1.</strong> Revisa la sección
                            <a href="productos.php" class="text-warning">Libros</a>
                            para descubrir manuales, campañas y reglamentos.
                        </li>
                        <li class="mb-2">
                            <strong>2.</strong> Crea tu cuenta y guarda tus títulos favoritos
                            para tenerlos siempre a la mano.
                        </li>
                        <li class="mb-2">
                            <strong>3.</strong> Usa Ludoverso como apoyo para preparar tus
                            sesiones: ideas rápidas, módulos completos y material de referencia.
                        </li>
                        <li class="mb-2">
                            <strong>4.</strong> Comparte la experiencia con otros jugadores y
                            ayuda a que el catálogo siga creciendo.
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </section>

</div>

<!-- Incluir Pie -->
<?php include("template/pie.php"); ?>
