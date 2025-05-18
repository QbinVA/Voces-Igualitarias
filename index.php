<?php
// index.php

require __DIR__ . '/config/db.php';                  // Conexión a MySQL
require __DIR__ . '/azure/config.php';               // Constantes Azure
require __DIR__ . '/azure/azure-translator.php';     // Función de traducción

// Idioma solicitado
$lang = $_GET['lang'] ?? 'es';

// Obtener categorías desde la base de datos
try {
    $sqlCategorias = "SELECT id_categoria, nombre FROM categorias";
    $stmtCat = $pdo->query($sqlCategorias);
    $categorias = $stmtCat->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al obtener categorías: " . $e->getMessage());
}

// Obtener publicaciones para el slider moderno (todas las publicaciones)
try {
    $sqlSlider = "SELECT id_noticia, fecha, titular, descripcion_corta, imagen_principal
                  FROM publicaciones
                  WHERE archivada = 0
                  ORDER BY fecha DESC";
    $stmtSlider = $pdo->query($sqlSlider);
    $sliderPubs = $stmtSlider->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al obtener publicaciones para el slider: " . $e->getMessage());
}

// Obtener publicaciones para el Swiper (filtrar por categoría si está seleccionada)
$categoriaSeleccionada = $_GET['categoria'] ?? '';

try {
    $sql = "SELECT id_noticia, fecha, titular, descripcion_corta, imagen_principal
            FROM publicaciones
            WHERE archivada = 0";
    if ($categoriaSeleccionada) {
        $sql .= " AND id_categoria = :categoria";
    }
    $sql .= " ORDER BY fecha DESC";

    $stmt = $pdo->prepare($sql);
    if ($categoriaSeleccionada) {
        $stmt->bindParam(':categoria', $categoriaSeleccionada, PDO::PARAM_INT);
    }
    $stmt->execute();
    $pubs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al obtener publicaciones: " . $e->getMessage());
}

?><!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Poder Igualitario</title>

  <!-- Estilos -->
  <link rel="stylesheet" href="views/css/index.css">
  <link rel="stylesheet" href="views/css/header.css">
  <link rel="stylesheet" href="views/css/footer.css">
  <link rel="stylesheet" href="views/css/font/font.css">
  <!-- Swiper CDN - Actualizado a la versión más reciente -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
  
  <style>
    /* Estilos para el mensaje destacado y nuevo slider */
    .featured-message {
      width: 80%;
      margin: 20px auto;
      padding: 25px;
      background-color: rgba(255, 255, 255, 0.85);
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      text-align: center;
      position: relative;
      z-index: 2;
    }
    
    .featured-message h3 {
      color: #009193;
      margin-bottom: 10px;
      font-size: 1.8em;
    }
    
    .featured-message p {
      font-size: 1.2em;
      line-height: 1.6;
      color: #333;
    }
    
    /* Pantalla transmisora de imágenes aleatorias */
    .image-transmitter {
      width: 85%;
      margin: 0 auto 30px;
      border-radius: 12px;
      overflow: hidden;
      position: relative;
      height: 350px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
      z-index: 1;
      border: 3px solid #01BDA3;
    }
    
    .image-transmitter .slide {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      opacity: 0;
      transition: opacity 0.6s ease;
      z-index: 1;
    }
    
    .image-transmitter .slide.active {
      opacity: 1;
      z-index: 2;
    }
    
    .image-transmitter .slide img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }
    
    .image-transmitter .slide-content {
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      padding: 20px;
      background: linear-gradient(to top, rgba(0,0,0,0.8), rgba(0,0,0,0));
      color: white;
      z-index: 3;
    }
    
    .image-transmitter .slide-title {
      font-size: 1.3em;
      margin-bottom: 5px;
      font-weight: bold;
      text-shadow: 1px 1px 3px rgba(0,0,0,0.8);
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      max-width: 90%;
    }
    
    .image-transmitter .slide-date {
      font-size: 0.9em;
      opacity: 0.8;
    }
    
    /* Estilo mejorado para el filtro de categorías */
    .filter-section {
      width: 80%;
      margin: 0 auto 20px;
      text-align: center;
    }
    
    .filter-section form {
      display: inline-flex;
      align-items: center;
      background-color: rgba(255, 255, 255, 0.85);
      padding: 10px 15px;
      border-radius: 25px;
      box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
    }
    
    .filter-section label {
      color: #009193;
      font-weight: bold;
      margin-right: 10px;
    }
    
    .filter-section select {
      background-color: #01BDA3;
      color: white;
      border: none;
      border-radius: 20px;
      padding: 8px 15px;
      font-size: 0.95em;
      cursor: pointer;
      outline: none;
      transition: all 0.3s ease;
      appearance: none;
      -webkit-appearance: none;
      -moz-appearance: none;
      background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
      background-repeat: no-repeat;
      background-position: right 10px center;
      background-size: 1em;
      padding-right: 30px;
    }
    
    .filter-section select:hover {
      background-color: #00a090;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }
    
    .filter-section select:focus {
      box-shadow: 0 0 0 3px rgba(1, 189, 163, 0.3);
    }
    
    /* Estilos para Swiper - Corregido para evitar desplazamiento horizontal */
    .swiper-container {
      width: 90%;
      margin: 0 auto;
      padding: 30px 0;
      overflow: hidden;  /* Cambiado de visible a hidden para evitar desplazamiento */
      position: relative;
    }
    
    .swiper-slide {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
      height: 340px;
      overflow: hidden;
      transition: transform 0.4s ease, box-shadow 0.4s ease;
      border-top: 3px solid #01BDA3;
      opacity: 0.7;
      transform: scale(0.9);
    }
    
    .swiper-slide-active {
      opacity: 1;
      transform: scale(1);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }
    
    .swiper-slide-next, .swiper-slide-prev {
      opacity: 0.85;
      transform: scale(0.95);
    }
    
    .swiper-slide:hover {
      transform: translateY(-5px) scale(1.01);
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.18);
    }
    
    .card-image {
      height: 160px;
      width: 100%;
      object-fit: cover;
      transition: transform 0.5s ease;
    }
    
    .swiper-slide:hover .card-image {
      transform: scale(1.05);
    }
    
    .card-content {
      padding: 15px;
    }
    
    .card-title {
      font-size: 16px;
      font-weight: bold;
      margin: 0 0 10px;
      color: #009193;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
      height: 44px;
      text-overflow: ellipsis;
    }
    
    .card-description {
      font-size: 14px;
      color: #555;
      margin-bottom: 10px;
      display: -webkit-box;
      -webkit-line-clamp: 3;
      -webkit-box-orient: vertical;
      overflow: hidden;
      height: 64px;
      text-overflow: ellipsis;
    }
    
    .card-date {
      font-size: 12px;
      color: #999;
      display: block;
      margin-top: 5px;
    }
    
    .card-more {
      display: inline-block;
      color: #01BDA3;
      font-size: 14px;
      font-weight: 500;
      margin-top: 5px;
      position: relative;
      overflow: hidden;
    }
    
    .card-more:after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 0%;
      height: 2px;
      background-color: #01BDA3;
      transition: width 0.3s ease;
    }
    
    .swiper-slide:hover .card-more:after {
      width: 100%;
    }
    
    /* Swiper Navigation y Pagination */
    .swiper-button-next, 
    .swiper-button-prev {
      color: #01BDA3;
      background: rgba(255, 255, 255, 0.9);
      width: 40px;
      height: 40px;
      border-radius: 50%;
      --swiper-navigation-size: 18px;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.3s ease;
    }
    
    .swiper-button-next:hover, 
    .swiper-button-prev:hover {
      background: #01BDA3;
      color: white;
      transform: scale(1.1);
    }
    
    /* Swiper Pagination */
    .swiper-pagination {
      position: relative;
      margin-top: 20px;
    }
    
    .swiper-pagination-bullet {
      background: #01BDA3;
      opacity: 0.5;
      width: 10px;
      height: 10px;
      transition: all 0.3s ease;
    }
    
    .swiper-pagination-bullet-active {
      opacity: 1;
      background: #01BDA3;
      transform: scale(1.2);
    }
    
    .section-title {
      text-align: center;
      color: white;
      margin: 30px 0 20px;
      font-size: 1.8em;
      text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
    }
    
    .no-news {
      text-align: center;
      background: rgba(255, 255, 255, 0.9);
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
      font-size: 1.2em;
      color: #555;
      width: 80%;
      margin: 0 auto;
    }
    
    /* Corrección para eliminar scroll horizontal en toda la página */
    html, body {
      max-width: 100%;
      overflow-x: hidden;
    }
  </style>
</head>

<?php
// 2) Abrimos buffer para TODO lo que SÍ queremos traducir:
//    <main> y el footer partial
ob_start();
?>

<body>
  <!-- Header fijo -->
  <?php include __DIR__ . '/views/layouts/header.php'; ?>

  <!-- Mensaje destacado (actualizado) -->
  <section class="featured-message">
    <h3>Bienvenidos a Voces Igualitarias</h3>
    <p>Descubre las últimas noticias y contenidos enfocados en igualdad, participación y cambio social.  
      ¡Siéntete libre de comentar en las noticias!</p>
  </section>

  <!-- Pantalla transmisora de imágenes aleatorias -->
  <section class="image-transmitter">
      <?php
      // Mezclamos las publicaciones para mostrarlas al azar
      $random_pubs = $sliderPubs;
      shuffle($random_pubs);
      $random_pubs = array_slice($random_pubs, 0, min(10, count($random_pubs)));

      foreach ($random_pubs as $i => $pub):
          // Preparamos correctamente la ruta de la imagen
          $imagen = htmlspecialchars(str_replace('../uploads/', '', $pub['imagen_principal']));
          $imgSrc = "uploads/" . $imagen;
      ?>
          <div class="slide <?= $i === 0 ? 'active' : '' ?>">
              <img src="<?= $imgSrc ?>" alt="<?= htmlspecialchars($pub['titular']) ?>">
              <div class="slide-content">
                  <h3 class="slide-title">
                      <?php
                      $title = htmlspecialchars($pub['titular']);
                      echo (strlen($title) > 40) ? substr($title, 0, 40) . '...' : $title;
                      ?>
                  </h3>
                  <div class="slide-date">
                      <?= ($lang === 'es')
                          ? date("d/m/Y", strtotime($pub['fecha']))
                          : date("m/d/Y", strtotime($pub['fecha'])); ?>
                  </div>
              </div>
          </div>
      <?php endforeach; ?>
  </section>

  <!-- Noticias -->
  <main>
    <h3 class="section-title">
      <?= $lang === 'es'
          ? 'Noticias sobre la igualdad de género'
          : 'News about gender equality' ?>
    </h3>
    <!-- Filtro de categorías con diseño renovado -->
    <section class="filter-section">
      <form method="GET" action="index.php">
        <input type="hidden" name="lang" value="<?= htmlspecialchars($lang) ?>">
        <label for="categoria">
          <?= $lang === 'es' ? 'Filtrar por categoría:' : 'Filter by category:' ?>
        </label>
        <select name="categoria" id="categoria" onchange="this.form.submit()">
          <option value="">
            <?= $lang === 'es' ? 'Todas las categorías' : 'All categories' ?>
          </option>
          <?php foreach ($categorias as $cat): ?>
            <option value="<?= $cat['id_categoria'] ?>"
              <?= $cat['id_categoria'] == $categoriaSeleccionada ? 'selected' : '' ?>>
              <?= htmlspecialchars($cat['nombre']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </form>
    </section>

    <!-- Swiper fluido corregido -->
    <?php if (empty($pubs)): ?>
      <p class="no-news">
        <?= $lang === 'es'
            ? 'No se encontraron noticias para esta categoría.'
            : 'No news found for this category.' ?>
      </p>
    <?php else: ?>
      <div class="swiper-container">
        <div class="swiper-wrapper">
          <?php foreach ($pubs as $pub): ?>
            <div class="swiper-slide">
              <a href="views/layouts/ver_publicacion.php?id=<?= $pub['id_noticia'] ?>&lang=<?= $lang ?>">
                <img src="uploads/<?= htmlspecialchars($pub['imagen_principal']) ?>" alt="" class="card-image">
                <div class="card-content">
                  <h5 class="card-title"><?= htmlspecialchars($pub['titular']) ?></h5>
                  <p class="card-description"><?= htmlspecialchars($pub['descripcion_corta']) ?></p>
                  <span class="card-date">
                    <?= ($lang === 'es')
                        ? date("d/m/Y", strtotime($pub['fecha']))
                        : date("m/d/Y", strtotime($pub['fecha'])); ?>
                  </span>
                  <div class="card-more">Ver más...</div>
                </div>
              </a>
            </div>
          <?php endforeach; ?>
        </div>
        <!-- Navegación del Swiper -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        <!-- Paginación del Swiper -->
        <div class="swiper-pagination"></div>
      </div>
    <?php endif; ?>
  </main>

  <!-- Footer (dentro del buffer para traducción) -->
  <?php include __DIR__ . '/views/layouts/footer.php'; ?>

  <!-- Swiper JS - Versión actualizada -->
  <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

  <script>
  document.addEventListener('DOMContentLoaded', () => {
    // Nueva pantalla transmisora de imágenes aleatorias
    const slides = document.querySelectorAll('.image-transmitter .slide');
    let currentIndex = 0;
    
    // Función para cambiar slide
    function changeSlide() {
      // Ocultar slide actual
      slides[currentIndex].classList.remove('active');
      
      // Seleccionar un índice aleatorio diferente al actual
      let newIndex;
      do {
        newIndex = Math.floor(Math.random() * slides.length);
      } while (newIndex === currentIndex && slides.length > 1);
      
      // Actualizar índice
      currentIndex = newIndex;
      
      // Mostrar nuevo slide
      slides[currentIndex].classList.add('active');
    }
    
    // Cambiar imagen cada 1.8 segundos (1800 ms)
    setInterval(changeSlide, 1800);

    // Inicializar Swiper mejorado para las tarjetas - CORREGIDO para evitar desplazamiento
    const swiper = new Swiper('.swiper-container', {
      slidesPerView: 1,
      spaceBetween: 20,
      grabCursor: true,
      centeredSlides: false,
      loop: false,
      speed: 600,
      autoplay: {
        delay: 3000,
        disableOnInteraction: false,
        pauseOnMouseEnter: true
      },
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
        dynamicBullets: true
      },
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
      breakpoints: {
        320: {
          slidesPerView: 1,
          spaceBetween: 10
        },
        640: {
          slidesPerView: 2,
          spaceBetween: 20,
        },
        768: {
          slidesPerView: 3,
          spaceBetween: 20,
        },
        1024: {
          slidesPerView: 3,
          spaceBetween: 30,
        },
      }
    });
  });
</script>
</body>
</html>
<?php
// 3) Capturamos todo el contenido traducible
$content = ob_get_clean();
// 4) Imprimir traducido si lang='en'
if ($lang === 'en') {
    echo azureTranslate($content, 'en', 'es', true);
} else {
    echo $content;
}
?>