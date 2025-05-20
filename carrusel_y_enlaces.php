<?php
require 'includes/connection.php';
?>
<?php include 'includes/header.php'; ?>
<main class="container mx-auto px-4 py-8">
  <!-- Carrusel de promociones -->
  <div id="carousel" class="relative overflow-hidden rounded-lg">
    <div class="carousel-inner relative w-full">
      <?php
      $promos = [
        ['img' => '/assets/images/promo1.jpg', 'alt'=>'Promo 1'],
        ['img' => '/assets/images/promo2.jpg', 'alt'=>'Promo 2'],
        ['img' => '/assets/images/promo3.jpg', 'alt'=>'Promo 3'],
      ];
      foreach($promos as $i => $promo): ?>
      <div class="carousel-item <?php echo $i===0?'block':'hidden'; ?> w-full">
        <img src="<?= $promo['img'] ?>" alt="<?= $promo['alt'] ?>" class="w-full h-64 object-cover">
      </div>
      <?php endforeach; ?>
    </div>
    <!-- controles -->
    <button id="prev" class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-gray-800 bg-opacity-50 text-white p-2">‹</button>
    <button id="next" class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-gray-800 bg-opacity-50 text-white p-2">›</button>
  </div>
  <script>
    // Carrusel automático ligero
    let idx = 0;
    const items = document.querySelectorAll('.carousel-item');
    const total = items.length;
    document.getElementById('next').addEventListener('click', ()=>{ items[idx].classList.add('hidden'); idx=(idx+1)%total; items[idx].classList.remove('hidden'); });
    document.getElementById('prev').addEventListener('click', ()=>{ items[idx].classList.add('hidden'); idx=(idx-1+total)%total; items[idx].classList.remove('hidden'); });
    setInterval(()=>{ document.getElementById('next').click(); }, 5000);
  </script>

  <!-- Enlaces rápidos -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-8">
    <a href="socios.php" class="bg-gray-800 p-6 rounded-lg hover:bg-gray-700 transition text-center">Registro Socios</a>
    <a href="compra.php" class="bg-gray-800 p-6 rounded-lg hover:bg-gray-700 transition text-center">Comprar Boletos</a>
    <a href="confiteria.php" class="bg-gray-800 p-6 rounded-lg hover:bg-gray-700 transition text-center">Confitería</a>
    <a href="search.php" class="bg-gray-800 p-6 rounded-lg hover:bg-gray-700 transition text-center">Buscar Películas</a>
  </div>
</main>
<?php include 'includes/footer.php'; ?>