<?php
// Cabecera ya se encarga de iniciar sesión.
// Aquí solo leemos el nombre del usuario web si existe.
include("template/cabecera.php");
$nombreUsuario = $nombreUsuarioWeb ?? null;
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Ludoverso — Biblioteca</title>

  <!-- Tipografías -->
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700;900&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

  <style>
  /* =========================================================
     Estilos mejorados — mantiene tus clases originales
     Añade animaciones, hover, partículas, parallax y microinteracciones
     ========================================================= */
     

  




  :root{
    --bg:#020617;
    --muted:#9ca3af;
    --accent:#8b5cf6;
    --gold:#f59e0b;
    --text:#e5e7eb;
    --card:#0b1220;
    --radius:12px;
  }

  /* Reset & base */
  *{box-sizing:border-box}
  html,body{height:100%}
  body{
    margin:0;
    font-family:Inter, system-ui, -apple-system, "Segoe UI", Roboto, Arial;
    background: radial-gradient(1000px 600px at 10% 10%, rgba(139,92,246,0.02), transparent 6%), var(--bg);
    color:var(--text);
    -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale;
  }
  a{color:inherit;text-decoration:none}
  .container{max-width:1200px;margin:0 auto;padding:0 20px;}

  /* NAVBAR ligero (mantener separable si tienes uno en la cabecera) */
  /* si ya tienes nav en cabecera, este bloque se ignora */
  .ludo-nav {
    position:fixed; top:14px; left:0; right:0; z-index:1400; display:flex; justify-content:center;
    pointer-events:none;
  }
  .ludo-nav .inner { pointer-events:auto; background:linear-gradient(180deg, rgba(2,6,23,0.06), rgba(2,6,23,0.02)); padding:8px 18px; border-radius:12px; border:1px solid rgba(255,255,255,0.03); backdrop-filter: blur(6px); box-shadow:0 10px 30px rgba(0,0,0,0.45); font-weight:600; color:var(--muted); }

  /* ====== ESTILOS SOLO PARA INDEX (conservados) ====== */

  .page-index-ludo {
      background: var(--bg);
      color: var(--text);
      padding-bottom:40px;
  }

  /* HERO con banner (mejorado): usamos la misma clase pero añadimos capas */
  .hero-ludoverso {
      position: relative;
      min-height: 75vh;
      color: #fff;
      border-radius:18px;
      overflow:visible;
      margin-top:32px;
      margin-left:20px;
      margin-right:20px;
  }

  /* fondo hero (respeta tu ruta original) */
  .hero-ludoverso .hero__bg {
      position:absolute; inset:0;
      background-image: url('img/dragon2.gif');
      background-size: cover;
      background-position: center;
      filter: brightness(.54) saturate(1.05) contrast(.98);
      transform: scale(1.02);
      transition: transform .6s ease-out, filter .4s;
      will-change: transform, filter;
      z-index:1;
    }

  .hero-ludoverso .overlay {
      position:relative;
      z-index:4;
      min-height: 75vh;
      display:flex;
      align-items:center;
      background: linear-gradient(
          90deg,
          rgba(5, 5, 5, 0) 100%,
          rgba(190, 166, 108, 1) 40%,
          rgba(17, 17, 16, 0) 100%
      );
      padding: 52px 0;
  }

  /* canvas de partículas sobre hero */
  #heroCanvas { position:absolute; inset:0; z-index:3; pointer-events:none; }

  .hero-grid{ display:flex; gap:28px; align-items:center; max-width:1200px; margin:0 auto; }

  .hero-left{ flex:1; max-width:720px; z-index:6; }
  .hero-right{ width:360px; text-align:right; z-index:6; }

  .display-4 {
    font-family:Cinzel, serif;
    font-weight:900;
    font-size: clamp(30px, 4.8vw, 48px);
    margin:0 0 12px;
    letter-spacing: -0.01em;
    text-shadow: 0 12px 36px rgba(226, 190, 90, 0.6);
     text-shadow: 0 0 15px #ffcc66, 0 0 30px #bb6600;
    animation: glow 3s ease-in-out infinite;
  }
  .lead{ color:#d1d5db; font-size:1.05rem; margin:0 0 18px; }

  /* Botones hero (mejorados) */
  .btn-hero-primary {
      background: linear-gradient(90deg, #3b82f6, var(--accent));
      border: none;
      color: #fff;
      display:inline-flex; align-items:center; gap:10px;
      padding:12px 18px; border-radius:10px; font-weight:800;
      box-shadow: 0 12px 36px rgba(59,130,246,0.10);
      transition: transform .14s ease, box-shadow .14s ease;
      position:relative; overflow:visible;
  }
  .btn-hero-primary:hover {
      transform: translateY(-4px);
      box-shadow: 0 30px 80px rgba(44, 117, 235, 0.12);
  }
  /* efecto fuego glow en hover */
  .btn-hero-primary::after {
      content:''; position:absolute; inset:0; background: radial-gradient(circle at 20% 20%, rgba(255, 162, 2, 0.12), transparent 30%); opacity:0; transition:opacity .28s;
  }
  .btn-hero-primary:hover::after { opacity:1; }

  .btn-hero-outline {
      border:1px solid rgba(255,255,255,0.06);
      color: var(--text);
      padding:12px 18px; border-radius:10px; font-weight:700;
      background: linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));
      transition: transform .14s, box-shadow .14s;
  }
  .btn-hero-outline:hover {
      transform: translateY(-4px);
      box-shadow: 0 20px 50px rgba(139,92,246,0.06);
      background: linear-gradient(90deg, rgba(139,92,246,0.06), rgba(245,158,11,0.04));
  }

  .hero-tag {
      background: rgba(15, 23, 42, 0.9);
      border-radius: 0.75rem;
      color: var(--text);
      border: 1px solid rgba(148, 163, 184, 0.12);
      box-shadow: 0 8px 30px rgba(0,0,0,0.6);
      padding:10px 14px;
      display:inline-block;
      z-index:6;
  }

  /* Secciones principales — conservadas y mejoradas */
  .section-what { background: transparent; color:var(--text); padding-top:28px; }
  .section-categories { background: radial-gradient(circle at top, #111827 0, #020617 55%); color:var(--text); }
  .section-how { background: transparent; color:var(--text); border-top:1px solid #1f2937; }

  /* Cards estilo Ludoverso mejoradas (bestiario) */
  .card-ludoverso {
      border-radius: 12px;
      border: 1px solid rgba(255,255,255,0.03);
      overflow: hidden;
      background: linear-gradient(180deg, rgba(255,255,255,0.02), rgba(0,0,0,0.03));
      color: var(--text);
      box-shadow: 0 40px 80px rgba(0,0,0,0.6);
      height: 100%;
      transform-style: preserve-3d;
      transition: transform .45s cubic-bezier(.2,.9,.2,1), box-shadow .35s;
      position:relative;
  }

  .card-ludoverso:hover {
      transform: translateY(-12px) rotateX(2deg) scale(1.02);
      box-shadow: 0 80px 140px rgba(0,0,0,0.75);
  }

  .card-ludoverso .img-top {
      height:200px; background-size:cover; background-position:center; transition:transform .6s, filter .45s;
  }
  .card-ludoverso:hover .img-top { transform: scale(1.07) translateY(-6px); filter:brightness(1.03); }

  .card-body { padding:16px; }
  .card-title { font-family:Cinzel, serif; margin-bottom:6px; }
  .card-text { color:var(--muted); }

  .badge-ludo {
    background: linear-gradient(180deg, #fbbf24, var(--gold));
    color: #111827; font-weight:700; padding:6px 10px; border-radius:999px;
    box-shadow: 0 10px 30px rgba(245,158,11,0.12);
    position:absolute; right:14px; top:14px;
  }

  .rune-line {
    height:6px; margin-top:12px; border-radius:999px;
    background: linear-gradient(90deg, rgba(139,92,246,0.9), rgba(245,158,11,0.8));
    box-shadow: 0 8px 30px rgba(139,92,246,0.14);
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
</head>
<body>

<div class="page-index-ludo">

 

  <!-- ====== HERO PRINCIPAL ====== -->
  <div class="hero-ludoverso" id="heroSection" aria-label="Hero Ludoverso">
    <!-- Canvas de partículas / magia -->
    <canvas id="heroCanvas" aria-hidden="true"></canvas>

    <!-- Fondo (usa tu ruta original) -->
    <div class="hero__bg" aria-hidden="true"></div>

    <div class="overlay d-flex align-items-center">
      <div class="container hero-content">
          <div class="hero-grid">
              <div class="hero-left">
                  <h1 class="display-4 font-weight-bold mb-3">
                      Bienvenido a Ludoverso<?php echo $nombreUsuario ? ', ' . htmlspecialchars($nombreUsuario) : ''; ?>
                  </h1>
                  <p class="lead mb-4">
                      Explora manuales de rol, campañas, bestiarios y wargames.
                      Construye tu propia biblioteca para la próxima sesión épica.
                  </p>

                  <a href="productos.php" class="btn-hero-primary" id="btnCatalogo">
                      Ver todos los libros
                  </a>

                  <?php if (!$nombreUsuario) { ?>
                      <a href="login.php" class="btn-hero-outline" id="btnLogin" style="margin-left:12px;">
                          Iniciar sesión / Registrarse
                      </a>
                  <?php } ?>

                  <p class="mt-3 mb-0">
                      <small class="text-light" style="color:#cbd5e1;">
                          Recursos listos para jugar: campañas completas, reglamentos, guías de director y más.
                      </small>
                  </p>
              </div>

              <div class="hero-right text-md-right mt-4 mt-md-0">
                  <div class="hero-tag px-3 py-2 d-inline-block">
                      <small class="text-uppercase" style="letter-spacing: .1em; color:var(--muted);">Novedad</small>
                      <div class="h5 mb-0" style="font-family:Cinzel, serif; color:var(--text);">
                          Colección de campañas y wargames 2025
                      </div>
                  </div>
              </div>
          </div>
      </div>
    </div>
  </div>

  <!-- ====== SECCIÓN: ¿QUÉ ES LUDOVERSO? ====== -->
  <section class="py-5 section-what reveal" id="what">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-6 mb-4 mb-md-0">
            <h2 class="section-title">¿Qué es Ludoverso?</h2>
            <p class="section-subtitle">
                Ludoverso es tu biblioteca digital de libros de rol y juegos de guerra,
                pensada para directores de juego, narradores y amantes de la estrategia.
            </p>
            <p>
                Encontrarás manuales básicos para iniciar en el rol, campañas completas
                listas para jugar, reglamentos tácticos y suplementos que enriquecen
                cualquier mesa de juego.
            </p>
            <p class="mb-0">
                Nuestra misión es que tengas todo lo necesario para contar mejores
                historias, dirigir batallas memorables y darle vida a tus mundos.
            </p>
        </div>
        <div class="col-md-6">
            <div class="card-ludoverso floaty">
                <div class="img-top" style="background-image:url('img/libro_destacado_01.png');"></div>
                <div class="card-body">
                  <span class="badge-ludo">Destacado</span>
                  <h5 class="card-title">Libros de rol</h5>
                  <p class="card-text">Reglas, guías y ayudas para dirigir campañas completas.</p>
                  <div class="rune-line" aria-hidden="true"></div>
                </div>
            </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ====== SECCIÓN: CATEGORÍAS DESTACADAS ====== -->
  <section class="py-5 section-categories reveal" id="categories">
    <div class="container">
        <h2 class="section-title text-center mb-3">Explora por tipo de contenido</h2>
        <p class="section-subtitle text-center mb-5">
            Filtra tu próxima lectura entre manuales de reglas, campañas, wargames y suplementos.
        </p>

        <div class="row">
            <!-- Manuales de Rol -->
            <div class="col-md-4 mb-4">
                <div class="card-ludoverso reveal card-ludoverso floaty">
                    <div class="img-top" style="background-image:url('img/categoria_manuales.png');"></div>
                    <div class="card-body">
                        <h5 class="card-title">Manuales del Jugador y del Director</h5>
                        <p class="card-text">
                            Reglas básicas, creación de personajes, equipo y herramientas
                            para dirigir campañas completas en distintos sistemas.
                        </p>
                        <div class="rune-line" aria-hidden="true"></div>
                    </div>
                </div>
            </div>

            <!-- Campañas y aventuras -->
            <div class="col-md-4 mb-4">
                <div class="card-ludoverso reveal card-ludoverso floaty">
                    <div class="img-top" style="background-image:url('img/categoria_campanas.png');"></div>
                    <div class="card-body">
                        <span class="badge-ludo">Campañas</span>
                        <h5 class="card-title">Campañas y aventuras listas para jugar</h5>
                        <p class="card-text">
                            Módulos con tramas, PNJ, mapas y ganchos preparados
                            para llevar directamente a la mesa sin partir desde cero.
                        </p>
                        <div class="rune-line" aria-hidden="true"></div>
                    </div>
                </div>
            </div>

            <!-- Wargames -->
            <div class="col-md-4 mb-4">
                <div class="card-ludoverso reveal card-ludoverso floaty">
                    <div class="img-top" style="background-image:url('img/categoria_wargame.png');"></div>
                    <div class="card-body">
                        <span class="badge-ludo">Wargame</span>
                        <h5 class="card-title">Reglamentos de escaramuzas y batallas</h5>
                        <p class="card-text">
                            Sistemas tácticos para recrear combates a pequeña y gran escala:
                            ideal para quienes disfrutan planear cada movimiento sobre el tablero.
                        </p>
                        <div class="rune-line" aria-hidden="true"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </section>

  <!-- ====== SECCIÓN: CÓMO EMPEZAR ====== -->
  <section class="py-5 section-how reveal" id="how">
    <div class="container">
        <div class="row">
            <div class="col-md-8 mb-4 mb-md-0">
                <h2 class="section-title">¿Primera vez en Ludoverso?</h2>
                <p>
                    <strong>1.</strong> Explora el catálogo de <strong>Libros</strong> y elige los títulos
                    que más se ajusten a tu estilo de juego.  
                    <strong>2.</strong> Crea tu cuenta para guardar favoritos y volver a encontrarlos al instante.  
                    <strong>3.</strong> Prepara tu mesa, reúne a tu grupo y comienza la aventura.
                </p>
                <p class="mb-0">
                    Si ya eres director de juego experimentado, aquí encontrarás nuevas campañas,
                    bestiarios, ayudas de juego y reglamentos tácticos para sorprender a tu mesa
                    cada semana.
                </p>
            </div>
            <div class="col-md-4 d-flex align-items-center justify-content-md-end">
                <div>
                    <a href="productos.php" class="btn-hero-primary" style="display:inline-block;padding:12px 16px;border-radius:10px;">Ir al catálogo de libros</a>
                    <br>
                    <?php if (!$nombreUsuario) { ?>
                        <a href="login.php" class="btn-hero-outline" style="display:inline-block;padding:12px 16px;border-radius:10px;margin-top:12px;">Iniciar sesión / Registrarse</a>
                    <?php } else { ?>
                        <a href="productos.php" class="btn-hero-outline" style="display:inline-block;padding:12px 16px;border-radius:10px;margin-top:12px;">Ver mi biblioteca</a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
  </section>

</div>

<!-- Incluir Pie -->
<?php include("template/pie.php"); ?>

<footer>
  <div class="container" style="padding:22px 0; text-align:center; color:var(--muted);">
    © <?php echo date('Y'); ?> Ludoverso • Diseñado para narradores y estrategas • <span style="color:var(--muted)">Hecho con magia</span>
  </div>
</footer>

<!-- ====== SCRIPTS: partículas, parallax, scroll reveal y microinteracciones ====== -->
<script>
/* ===== NAVBAR SCROLL EFFECT (non-intrusive) ===== */
(function(){
  // Añade clase 'scrolled' al body para permitir estilos si fuese necesario
  function onScroll(){
    if(window.scrollY > 36) document.body.classList.add('scrolled'); else document.body.classList.remove('scrolled');
  }
  window.addEventListener('scroll', onScroll, {passive:true});
  onScroll();
})();

/* ===== SCROLL REVEAL (IntersectionObserver) ===== */
(function(){
  const reveals = document.querySelectorAll('.reveal');
  if(!('IntersectionObserver' in window)) {
    reveals.forEach(r => r.classList.add('visible'));
    return;
  }
  const obs = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if(entry.isIntersecting){
        entry.target.classList.add('visible');
        obs.unobserve(entry.target);
      }
    });
  }, {threshold: 0.12});
  reveals.forEach(r=>obs.observe(r));
})();

/* ===== HERO PARALLAX (mouse) ===== */
(function(){
  const hero = document.getElementById('heroSection');
  const bg = hero && hero.querySelector('.hero__bg');
  if(!hero || !bg) return;
  hero.addEventListener('mousemove', e => {
    const rect = hero.getBoundingClientRect();
    const x = (e.clientX - rect.left) / rect.width - 0.5;
    const y = (e.clientY - rect.top) / rect.height - 0.5;
    // sutil transform para parallax
    bg.style.transform = `translate(${x*16}px, ${y*10}px) scale(1.04)`;
    bg.style.filter = `brightness(${0.52 + Math.abs(y)*0.06}) saturate(1.05)`;
  });
  hero.addEventListener('mouseleave', () => {
    bg.style.transform = 'translate(0,0) scale(1.02)';
    bg.style.filter = 'brightness(.54) saturate(1.05)';
  });
})();

/* ===== HERO PARTICLES (canvas): fuego, chispas y magia ===== */
(function(){
  const canvas = document.getElementById('heroCanvas');
  if(!canvas) return;
  const ctx = canvas.getContext('2d');
  let dpr = window.devicePixelRatio || 1;

  function resize(){
    dpr = window.devicePixelRatio || 1;
    canvas.width = canvas.clientWidth * dpr;
    canvas.height = canvas.clientHeight * dpr;
    ctx.setTransform(dpr,0,0,dpr,0,0);
  }

  // set canvas size to heroSection bounds
  function fitToHero(){
    const hero = document.getElementById('heroSection');
    if(!hero) return;
    const rect = hero.getBoundingClientRect();
    canvas.style.position = 'absolute';
    canvas.style.left = rect.left + 'px';
    canvas.style.top = rect.top + 'px';
    canvas.style.width = rect.width + 'px';
    canvas.style.height = rect.height + 'px';
    canvas.width = rect.width * dpr;
    canvas.height = rect.height * dpr;
    ctx.setTransform(dpr,0,0,dpr,0,0);
  }

  

  function spawn(x,y,opts = {}) {
    const n = opts.burst ? 18 : (2 + Math.floor(Math.random()*3));
    for(let i=0;i<n;i++){
      particles.push({
        x: x + (Math.random()-0.5)*120,
        y: y + (Math.random()-0.5)*60,
        vx: (Math.random()-0.5)*1.6,
        vy: - (0.6 + Math.random()*1.8),
        life: 40 + Math.random()*120,
        size: 1 + Math.random()*4,
        color: colors[Math.floor(Math.random()*colors.length)],
        fade: 0.96 + Math.random()*0.02
      });
    }
  }

  function step(){
    ctx.clearRect(0,0,canvas.width/dpr, canvas.height/dpr);
    // ambient spawn near bottom sometimes
    if(Math.random() < 0.5) {
      const w = canvas.clientWidth;
      spawn(Math.random()*w, canvas.clientHeight*0.92, {});
    }
    for(let i=particles.length-1;i>=0;i--){
      const p = particles[i];
      p.x += p.vx * 1.2;
      p.y += p.vy * 1.2;
      p.vy += 0.02;
      p.life--;
      // draw
      ctx.beginPath();
      ctx.fillStyle = p.color;
      ctx.globalAlpha = Math.max(0, Math.min(1, p.life/120));
      ctx.shadowColor = p.color;
      ctx.shadowBlur = Math.max(6, p.size*6);
      ctx.arc(p.x, p.y, p.size, 0, Math.PI*2);
      ctx.fill();
      if(p.life <= 0 || p.y > canvas.clientHeight + 60) particles.splice(i,1);
    }
    ctx.globalAlpha = 1;
    requestAnimationFrame(step);
  }

  // startup
  function init(){
    fitToHero();
    step();
  }

  // resize handling
  window.addEventListener('resize', ()=>{
    fitToHero();
  });

  // spawn burst on hero CTA hover/focus
  const ctas = document.querySelectorAll('.btn-hero-primary, .btn-hero-outline');
  ctas.forEach(btn=>{
    btn.addEventListener('mouseenter', (e)=>{
      const rect = btn.getBoundingClientRect();
      const heroRect = document.getElementById('heroSection').getBoundingClientRect();
      const cx = rect.left + rect.width/2 - heroRect.left;
      const cy = rect.top + rect.height/2 - heroRect.top;
      spawn(cx, cy, {burst:true});
    });
    btn.addEventListener('focus', (e)=>{
      const rect = btn.getBoundingClientRect();
      const heroRect = document.getElementById('heroSection').getBoundingClientRect();
      const cx = rect.left + rect.width/2 - heroRect.left;
      const cy = rect.top + rect.height/2 - heroRect.top;
      spawn(cx, cy, {burst:true});
    });
  });

  // init when DOM ready and hero exists
  window.addEventListener('load', ()=>{
    init();
  });

})();

/* ===== FLOATING & MICROINTERACTIONS ===== */
(function(){
  // slight float on .floaty (already CSS) — reveal timing
  const floatCards = document.querySelectorAll('.card-ludoverso.floaty');
  floatCards.forEach((card, i) => {
    setTimeout(()=> card.classList.add('visible'), 300 + (i*120));
  });
})();

/* ===== SMOOTH SCROLL CTA ===== */
(function(){
  document.getElementById('btnCatalogo')?.addEventListener('click', function(e){
    e.preventDefault();
    const el = document.querySelector('.section-categories') || document.querySelector('.grid');
    if(el) el.scrollIntoView({behavior:'smooth'});
  });
})();

</script>

</body>
</html>
