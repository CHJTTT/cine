<?php
require 'connection.php'; // CORREGIDO
include 'header_comun.php'; // CORREGIDO - Asume que has creado cine_star/header_comun.php
?>
<main class="container mx-auto px-4 py-8">
  <h2 class="text-2xl font-bold mb-4 text-red-400">Buscador de Películas</h2>
  <form method="GET" action="search.php" class="mb-6">
    <div class="flex space-x-2">
      <input type="text" name="q" placeholder="Buscar película o actor..." class="w-full p-2 rounded bg-gray-700 text-white" value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>" required>
      <button type="submit" class="bg-red-600 py-2 px-4 rounded hover:bg-red-700">Buscar</button>
    </div>
  </form>

  <?php
  if (isset($_GET['q']) && !empty(trim($_GET['q']))) {
      if (isset($pdo)) {
          $searchTerm = trim($_GET['q']);
          $queryParam = '%'.$searchTerm.'%';
          // Asegúrate que tu tabla peliculas tiene las columnas titulo, actor_principal, imagen
          $stmt = $pdo->prepare("SELECT id, titulo, actor_principal, imagen, sinopsis FROM peliculas WHERE titulo LIKE ? OR actor_principal LIKE ? ORDER BY titulo ASC");
          $stmt->execute([$queryParam, $queryParam]);
          $results = $stmt->fetchAll();

          if($results){
              echo '<h3 class="text-xl font-semibold mb-4">Resultados para "'.htmlspecialchars($searchTerm).'"</h3>';
              echo '<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">';
              foreach($results as $r){
                  echo '<div class="bg-gray-800 p-4 rounded-lg shadow-md flex flex-col">';
                  echo '<h4 class="text-lg font-semibold mb-2 text-red-400">'.htmlspecialchars($r['titulo']).'</h4>';
                  
                  // Mostrar imagen si existe y la ruta es correcta
                  if(!empty($r['imagen'])){
                      // Asume que las imágenes de películas también están en assets/images/
                      // Y que $r['imagen'] solo contiene el nombre del archivo, ej: "pelicula_poster.jpg"
                      // Si $r['imagen'] contiene una ruta completa o incorrecta, esto no funcionará.
                      $rutaImagenPelicula = 'assets/images/' . htmlspecialchars($r['imagen']); // Ajusta si es necesario
                      if (file_exists($rutaImagenPelicula)) { // Verificar si el archivo existe
                           echo '<img src="'.$rutaImagenPelicula.'" alt="'.htmlspecialchars($r['titulo']).'" class="w-full h-64 object-cover rounded mb-2">';
                      } else {
                           echo '<div class="w-full h-64 bg-gray-700 rounded mb-2 flex items-center justify-center text-gray-500">Imagen no disponible</div>';
                      }
                  } else {
                       echo '<div class="w-full h-64 bg-gray-700 rounded mb-2 flex items-center justify-center text-gray-500">Sin imagen</div>';
                  }
                  
                  echo '<p class="text-gray-400 mb-1 text-sm">Actor Principal: '.( !empty($r['actor_principal']) ? htmlspecialchars($r['actor_principal']) : 'N/A' ).'</p>';
                  if(!empty($r['sinopsis'])){
                      echo '<p class="text-gray-300 text-sm mb-3 flex-grow">'.nl2br(htmlspecialchars(substr($r['sinopsis'], 0, 100))). (strlen($r['sinopsis']) > 100 ? '...' : '').'</p>';
                  }
                  // Podrías añadir un botón para ver detalles o comprar
                  // echo '<a href="compra.php?pelicula_id='.$r['id'].'" class="mt-auto bg-red-500 text-white py-2 px-3 rounded text-center hover:bg-red-600 text-sm">Comprar Boletos</a>';
                  echo '</div>'; // Cierre de la tarjeta de película
              }
              echo '</div>'; // Cierre del grid
          } else {
              echo '<p class="text-gray-300">No se encontraron resultados para "'.htmlspecialchars($searchTerm).'". Intenta con otros términos.</p>';
          }
      } else {
          echo '<p class="text-gray-300">Error: No se pudo conectar a la base de datos para realizar la búsqueda.</p>';
      }
  } elseif (isset($_GET['q']) && empty(trim($_GET['q']))) {
      echo '<p class="text-gray-300">Por favor, ingresa un término de búsqueda.</p>';
  }
  ?>
</main>
<?php include 'piedepaginacomun.php'; // CORREGIDO - Asume que has creado cine_star/piedepaginacomun.php ?>