<?php
require 'connection.php'; // CORREGIDO
include 'header_comun.php'; // CORREGIDO - Asume que has creado cine_star/header_comun.php
?>
<main class="container mx-auto px-4 py-8">
  <h2 class="text-2xl font-bold mb-4 text-red-400">Comprar Boletos</h2>
  <?php
  // Asegurarse de que $pdo está disponible
  if (isset($pdo)) {
      // Obtener películas disponibles
      try {
        $pelisStmt = $pdo->query("SELECT id, titulo, estreno FROM peliculas ORDER BY estreno DESC"); // Especificar columnas
        $peliculas = $pelisStmt->fetchAll();

        // Procesar compra
        if(
          $_SERVER['REQUEST_METHOD']==='POST' &&
          isset($_POST['pelicula'], $_POST['funcion'], $_POST['asientos'], $_POST['confiteria']) &&
          is_array($_POST['asientos']) && !empty($_POST['asientos']) // Asegurarse que asientos es un array y no está vacío
        ){
          $pelicula_id = (int)$_POST['pelicula'];
          $funcion = $_POST['funcion'];
          $asientos_array = $_POST['asientos']; // Mantener como array para conteo
          $asientos_str = implode(', ', $asientos_array); // String para guardar en BD
          $confiteria_seleccionada = ($_POST['confiteria']==='si')?'SI':'NO'; // Renombrado para claridad
          $precio_por_asiento = 25.00; // Definir precio por asiento
          $precio_total = $precio_por_asiento * count($asientos_array);

          // Insertar compra
          $stmt = $pdo->prepare(
            "INSERT INTO compras (pelicula_id, funcion, asientos, monto, confiteria_seleccionada) VALUES (?,?,?,?,?)" // Usar confiteria_seleccionada
          );
          $stmt->execute([$pelicula_id, $funcion, $asientos_str, $precio_total, $confiteria_seleccionada]);
          $compra_id = $pdo->lastInsertId();

          // Mostrar comprobante
          echo '<div class="bg-gray-800 p-6 rounded-lg shadow-md">';
          echo '<h3 class="text-xl font-semibold mb-4 text-green-400">Comprobante de Compra</h3>';
          echo '<p class="text-gray-300">Número de Compra: <span class="font-semibold">'.$compra_id.'</span></p>';

          // Título película (buscar de nuevo o usar el array $peliculas si aún es accesible y completo)
          $titulo_pelicula = "Desconocida";
          foreach($peliculas as $p_info){
              if($p_info['id'] == $pelicula_id){
                  $titulo_pelicula = $p_info['titulo'];
                  break;
              }
          }
          echo '<p class="text-gray-300">Película: <span class="font-semibold">'.htmlspecialchars($titulo_pelicula).'</span></p>';
          echo '<p class="text-gray-300">Función: <span class="font-semibold">'.htmlspecialchars($funcion).'</span></p>';
          echo '<p class="text-gray-300">Asientos: <span class="font-semibold">'.htmlspecialchars($asientos_str).'</span></p>';
          echo '<p class="text-gray-300">Pidió en Confitería: <span class="font-semibold">'.htmlspecialchars($confiteria_seleccionada).'</span></p>';
          echo '<p class="text-gray-300">Total Pagado: <span class="font-semibold text-red-500">S/ '.number_format($precio_total,2).'</span></p>';
          echo '<p class="text-green-400 font-semibold mt-4">¡Gracias por tu compra! ¡Disfruta tu función!</p>';
          echo '</div>';
          // Es importante incluir el footer y salir si se muestra el comprobante
          include 'piedepaginacomun.php'; // CORREGIDO - Asume que has creado cine_star/piedepaginacomun.php
          exit;
        }
      } catch (PDOException $e) {
        echo '<div class="bg-red-600 text-white p-2 rounded mb-4">Error al obtener películas: '.htmlspecialchars($e->getMessage()).'</div>';
        $peliculas = []; // Asegurar que $peliculas es un array vacío si falla la consulta
      }
  } else {
      echo '<div class="bg-red-600 text-white p-2 rounded mb-4">Error: No se pudo establecer la conexión a la base de datos.</div>';
      $peliculas = []; // Asegurar que $peliculas es un array vacío si no hay $pdo
  }
  ?>

  <!-- Formulario de compra (solo se muestra si no se ha procesado una compra exitosa) -->
  <form action="compra.php" method="POST" class="bg-gray-800 p-6 rounded-lg shadow-md space-y-6">
    <div>
      <label for="pelicula" class="block text-gray-200 mb-2">Película:</label>
      <select name="pelicula" id="pelicula" required class="w-full p-2 rounded bg-gray-700 text-white">
        <?php if (!empty($peliculas)): ?>
            <?php foreach($peliculas as $p): ?>
              <option value="<?= htmlspecialchars($p['id']) ?>"><?= htmlspecialchars($p['titulo']) ?> (Estreno: <?= htmlspecialchars($p['estreno'] ?? 'N/A') ?>)</option>
            <?php endforeach; ?>
        <?php else: ?>
            <option value="">No hay películas disponibles</option>
        <?php endif; ?>
      </select>
    </div>
    <div>
      <label for="funcion" class="block text-gray-200 mb-2">Función:</label>
      <select name="funcion" id="funcion" required class="w-full p-2 rounded bg-gray-700 text-white">
        <option value="16:00">16:00</option>
        <option value="19:00">19:00</option>
        <option value="22:00">22:00</option>
      </select>
    </div>
    <div>
      <label class="block text-gray-200 mb-2">Asientos:</label>
      <div class="grid grid-cols-10 gap-2" id="salaselector">
        <!-- asientos generados vía JS -->
      </div>
      <!-- Usaremos múltiples inputs hidden para los asientos o un solo input que actualizaremos con JS -->
      <div id="asientosSeleccionadosHidden">
          <!-- Aquí JS insertará los inputs hidden para cada asiento -->
      </div>
    </div>
    <div>
      <label class="block text-gray-200 mb-2">¿Compró en la Confitería?</label>
      <div class="flex items-center space-x-4">
        <label><input type="radio" name="confiteria" value="si" required> Sí</label>
        <label><input type="radio" name="confiteria" value="no" checked> No</label> <!-- Añadido checked por defecto a "No" -->
      </div>
    </div>
    <button type="submit" class="bg-red-600 py-2 px-4 rounded hover:bg-red-700">Comprar</button>
  </form>
</main>
<?php include 'piedepaginacomun.php'; // CORREGIDO - Asume que has creado cine_star/piedepaginacomun.php ?>

<script>
  const sala = document.getElementById('salaselector');
  const asientosSeleccionadosHidden = document.getElementById('asientosSeleccionadosHidden');
  let asientosSeleccionadosArray = [];

  if (sala && asientosSeleccionadosHidden) {
    for(let f=0; f<10; f++){ // Filas A-J
      for(let a=1; a<=10; a++){ // Asientos 1-10
        // Excluir J9, J10 como en tu ejemplo original
        if(f===9 && (a===9||a===10)) continue;

        const asientoDiv = document.createElement('div');
        const asientoNombre = String.fromCharCode(65+f)+a;
        asientoDiv.textContent = asientoNombre;
        asientoDiv.className = 'p-2 bg-gray-600 rounded cursor-pointer text-center hover:bg-gray-500';
        asientoDiv.dataset.asiento = asientoNombre; // Guardar nombre del asiento

        asientoDiv.onclick = ()=>{
          const nombre = asientoDiv.dataset.asiento;
          if(asientosSeleccionadosArray.includes(nombre)){
            // Deseleccionar
            asientosSeleccionadosArray = asientosSeleccionadosArray.filter(x => x !== nombre);
            asientoDiv.classList.remove('bg-red-600', 'text-white');
            asientoDiv.classList.add('bg-gray-600');
            // Remover input hidden
            const inputToRemove = document.querySelector(`input[name="asientos[]"][value="${nombre}"]`);
            if (inputToRemove) inputToRemove.remove();
          } else {
            // Seleccionar
            asientosSeleccionadosArray.push(nombre);
            asientoDiv.classList.remove('bg-gray-600');
            asientoDiv.classList.add('bg-red-600', 'text-white');
            // Añadir input hidden
            const newInput = document.createElement('input');
            newInput.type = 'hidden';
            newInput.name = 'asientos[]';
            newInput.value = nombre;
            asientosSeleccionadosHidden.appendChild(newInput);
          }
        };
        sala.appendChild(asientoDiv);
      }
    }
  }
</script>