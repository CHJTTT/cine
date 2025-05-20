<?php
require 'includes/connection.php';
?>
<?php include 'includes/header.php'; ?>
<main class="container mx-auto px-4 py-8">
  <h2 class="text-2xl font-bold mb-4 text-red-400">Gestión de Socios</h2>

  <!-- Formulario de registro -->
  <div class="bg-gray-800 p-6 rounded-lg shadow-md mb-8">
    <h3 class="text-xl font-semibold mb-4">Registrar Nuevo Socio</h3>
    <?php if(
      isset($_GET['success']) && $_GET['success']=='1'
    ): ?>
      <div class="bg-green-600 text-white p-2 rounded mb-4">Socio registrado correctamente.</div>
    <?php endif; ?>
    <form action="socios.php" method="POST" class="space-y-4">
      <div>
        <label class="block text-gray-200">DNI:</label>
        <input type="text" name="dni" required class="w-full p-2 rounded bg-gray-700 text-white"/>
      </div>
      <div>
        <label class="block text-gray-200">Nombres:</label>
        <input type="text" name="nombres" required class="w-full p-2 rounded bg-gray-700 text-white"/>
      </div>
      <div>
        <label class="block text-gray-200">Correo:</label>
        <input type="email" name="correo" required class="w-full p-2 rounded bg-gray-700 text-white"/>
      </div>
      <div>
        <label class="block text-gray-200">Edad:</label>
        <input type="number" name="edad" min="0" required class="w-full p-2 rounded bg-gray-700 text-white"/>
      </div>
      <div>
        <label class="block text-gray-200">Género:</label>
        <select name="genero" required class="w-full p-2 rounded bg-gray-700 text-white">
          <option value="M">Masculino</option>
          <option value="F">Femenino</option>
          <option value="O">Otro</option>
        </select>
      </div>
      <button type="submit" name="action" value="register" class="bg-red-600 py-2 px-4 rounded hover:bg-red-700">Registrar</button>
    </form>
  </div>

  <?php
  // Procesar formulario
  if($_SERVER['REQUEST_METHOD']==='POST' && $_POST['action']==='register'){
    $dni = trim($_POST['dni']);
    $nombres = trim($_POST['nombres']);
    $correo = trim($_POST['correo']);
    $edad = (int)$_POST['edad'];
    $genero = $_POST['genero'];

    // Inserción segura
    $stmt = $pdo->prepare("INSERT INTO socios (dni, nombres, correo, edad, genero) VALUES (?,?,?,?,?)");
    try {
      $stmt->execute([$dni, $nombres, $correo, $edad, $genero]);
      header('Location: socios.php?success=1');
      exit;
    } catch(PDOException $e) {
      echo '<div class="bg-red-600 text-white p-2 rounded mb-4">Error: '.htmlspecialchars($e->getMessage()).'</div>';
    }
  }
  ?>

  <!-- Listado de socios -->
  <div class="bg-gray-800 p-6 rounded-lg shadow-md">
    <h3 class="text-xl font-semibold mb-4">Socios Registrados</h3>
    <table class="min-w-full bg-gray-700">
      <thead>
        <tr class="bg-gray-800 text-left">
          <th class="py-2 px-4">DNI</th>
          <th class="py-2 px-4">Nombres</th>
          <th class="py-2 px-4">Correo</th>
          <th class="py-2 px-4">Edad</th>
          <th class="py-2 px-4">Género</th>
          <th class="py-2 px-4">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $stmt = $pdo->query("SELECT * FROM socios ORDER BY creado_en DESC");
        while($row = $stmt->fetch()): ?>
        <tr class="border-b border-gray-600">
          <td class="py-2 px-4"><?= htmlspecialchars($row['dni']) ?></td>
          <td class="py-2 px-4"><?= htmlspecialchars($row['nombres']) ?></td>
          <td class="py-2 px-4"><?= htmlspecialchars($row['correo']) ?></td>
          <td class="py-2 px-4"><?= htmlspecialchars($row['edad']) ?></td>
          <td class="py-2 px-4"><?= htmlspecialchars($row['genero']) ?></td>
          <td class="py-2 px-4">
            <form action="delete_socios.php" method="POST" onsubmit="return confirm('¿Eliminar socio?');">
              <input type="hidden" name="dni" value="<?= htmlspecialchars($row['dni']) ?>" />
              <button type="submit" class="bg-red-600 py-1 px-3 rounded hover:bg-red-700">Eliminar</button>
            </form>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

</main>
<?php include 'includes/footer.php'; ?>