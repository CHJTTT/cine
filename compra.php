<?php
require 'includes/connection.php';
?>
<?php include 'includes/header.php'; ?>
<main class="container mx-auto px-4 py-8">
  <h2 class="text-2xl font-bold mb-4 text-red-400">Comprar Boletos</h2>
  <?php
  // Obtener películas disponibles
  $pelisStmt = $pdo->query("SELECT * FROM peliculas ORDER BY estreno DESC");
  $peliculas = $pelisStmt->fetchAll();

  // Procesar compra
  if(
    $_SERVER['REQUEST_METHOD']==='POST' &&
    isset($_POST['pelicula'], $_POST['funcion'], $_POST['asientos'], $_POST['confiteria'])
  ){
    $pelicula_id = (int)$_POST['pelicula'];
    $funcion = $_POST['funcion'];
    $asientos = implode(', ', $_POST['asientos']);
    $confiteria = ($_POST['confiteria']==='si')?'SI':'NO';
    $precio = 25.00 * count($_POST['asientos']);
    // Insertar compra
    $stmt = $pdo->prepare(
      "INSERT INTO compras (pelicula_id, funcion, asientos, monto, confiteria) VALUES (?,?,?,?,?)"
    );
    $stmt->execute([$pelicula_id, $funcion, $asientos, $precio, $confiteria]);
    $compra_id = $pdo->lastInsertId();
    // Mostrar comprobante
    echo '<div class="bg-gray-800 p-6 rounded-lg shadow-md">';
    echo '<h3 class="text-xl font-semibold mb-4 text-green-400">Comprobante de Compra</h3>';
    echo '<p class="text-gray-300">Número: <span class="font-semibold">'.$compra_id.'</span></p>';
    // Título película
    $t = array_filter($peliculas, fn($p)=> $p['id']==$pelicula_id);
    $titulo = $t ? array_values($t)[0]['titulo'] : '';
    echo '<p class="text-gray-300">Película: <span class="font-semibold">'.htmlspecialchars($titulo).'</span></p>';
    echo '<p class="text-gray-300">Función: <span class="font-semibold">'.htmlspecialchars($funcion).'</span></p>';
    echo '<p class="text-gray-300">Asientos: <span class="font-semibold">'.htmlspecialchars($asientos).'</span></p>';
    echo '<p class="text-gray-300">Confitería: <span class="font-semibold">'.htmlspecialchars($confiteria).'</span></p>';
    echo '<p class="text-gray-300">Total: <span class="font-semibold text-red-500">S/ '.number_format($precio,2).'</span></p>';
    echo '<p class="text-green-400 font-semibold mt-4">¡Disfruta tu función!</p>';
    echo '</div>';
    exit;
  }
  ?>

  <!-- Formulario de compra -->
  <form action="compra.php" method="POST" class="bg-gray-800 p-6 rounded-lg shadow-md space-y-6">
    <div>
      <label class="block text-gray-200 mb-2">Película:</label>
      <select name="pelicula" id="pelicula" required class="w-full p-2 rounded bg-gray-700 text-white">
        <?php foreach($peliculas as $p): ?>
          <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['titulo']) ?> (Estreno: <?= $p['estreno'] ?>)</option>
        <?php endforeach; ?>
      </select>
    </div>
    <div>
      <label class="block text-gray-200 mb-2">Función:</label>
      <select name="funcion" required class="w-full p-2 rounded bg-gray-700 text-white">
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
      <input type="hidden" name="asientos[]" id="asientosInput">
    </div>
    <div>
      <label class="block text-gray-200 mb-2">¿Compró en la Confitería?</label>
      <div class="flex items-center space-x-4">
        <label><input type="radio" name="confiteria" value="si" required> Sí</label>
        <label><input type="radio" name="confiteria" value="no"> No</label>
      </div>
    </div>
    <button type="submit" class="bg-red-600 py-2 px-4 rounded hover:bg-red-700">Comprar</button>
  </form>
</main>
<?php include 'includes/footer.php'; ?>

<script>
  // Generar asientos 10x10, excluir J9,J10
  const sala = document.getElementById('salaselector');
  let seleccionados = [];
  for(let f=0; f<10; f++){
    for(let a=1; a<=10; a++){
      if(f===9 && (a===9||a===10)) continue;
      const d = document.createElement('div');
      d.textContent = String.fromCharCode(65+f)+a;
      d.className = 'p-2 bg-gray-600 rounded cursor-pointer text-center';
      d.onclick = ()=>{
        const txt = d.textContent;
        if(seleccionados.includes(txt)){
          seleccionados = seleccionados.filter(x=>x!==txt);
          d.classList.remove('bg-red-600');
          d.classList.add('bg-gray-600');
        } else {
          seleccionados.push(txt);
          d.classList.remove('bg-gray-600');
          d.classList.add('bg-red-600');
        }
        // actualizar input hidden
        const input = document.getElementById('asientosInput');
        input.value = seleccionados;
      };
      sala.appendChild(d);
    }
  }
</script>