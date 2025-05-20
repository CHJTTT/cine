<?php
require 'includes/connection.php';
?>
<?php include 'includes/header.php'; ?>
<main class="container mx-auto px-4 py-8">
  <h2 class="text-2xl font-bold mb-4 text-red-400">Confitería</h2>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <?php
    $combos = [
      ['id'=>1,'titulo'=>'Combo Clásico','desc'=>'Palomitas grandes + 2 bebidas','precio'=>25.00,'img'=>'/assets/images/combo1.jpg'],
      ['id'=>2,'titulo'=>'Combo Dulce','desc'=>'Palomitas acarameladas + 2 bebidas','precio'=>27.00,'img'=>'/assets/images/combo2.jpg'],
      ['id'=>3,'titulo'=>'Combo Nachos','desc'=>'Nachos con queso + 2 bebidas','precio'=>30.00,'img'=>'/assets/images/combo3.jpg'],
    ];
    foreach($combos as $combo): ?>
    <div class="bg-gray-800 p-6 rounded-lg shadow-md flex flex-col">
      <img src="<?= $combo['img'] ?>" alt="<?= $combo['titulo'] ?>" class="rounded mb-4 h-48 object-cover">
      <h3 class="text-xl font-semibold mb-2"><?= $combo['titulo'] ?></h3>
      <p class="text-gray-300 mb-4"><?= $combo['desc'] ?></p>
      <p class="text-red-500 font-bold text-lg mb-4">S/ <?= number_format($combo['precio'],2) ?></p>
      <button onclick="addCart(<?= $combo['id'] ?>)" class="mt-auto bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700">Agregar al Carrito</button>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Carrito modal -->
  <div id="cartModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
    <div class="bg-gray-800 p-6 rounded-lg shadow-md w-96">
      <h3 class="text-xl font-semibold text-red-400 mb-4">Tu Carrito</h3>
      <ul id="cartList" class="space-y-2 text-gray-300"></ul>
      <p class="mt-4 font-semibold text-gray-200">Total: S/ <span id="cartTotal">0.00</span></p>
      <div class="mt-6 flex justify-end space-x-4">
        <button onclick="clearCart()" class="bg-gray-600 py-1 px-3 rounded hover:bg-gray-500">Vaciar</button>
        <button onclick="checkout()" class="bg-red-600 py-1 px-3 rounded hover:bg-red-700">Pagar</button>
      </div>
      <button onclick="toggleCart()" class="absolute top-2 right-2 text-gray-400 hover:text-white">✕</button>
    </div>
  </div>
</main>
<?php include 'includes/footer.php'; ?>
<script>
  let cart = [];
  function addCart(id){
    const combo = {1:['Combo Clásico',25.00],2:['Combo Dulce',27.00],3:['Combo Nachos',30.00]}[id];
    cart.push({titulo:combo[0],precio:combo[1]}); updateCart();
    toggleCart();
  }
  function updateCart(){
    const list = document.getElementById('cartList'); list.innerHTML=''; let total=0;
    cart.forEach((item,i)=>{ total+=item.precio; list.innerHTML+=`<li>${item.titulo} - S/ ${item.precio.toFixed(2)}</li>`; });
    document.getElementById('cartTotal').textContent=total.toFixed(2);
  }
  function toggleCart(){ document.getElementById('cartModal').classList.toggle('hidden'); }
  function clearCart(){ cart=[]; updateCart(); }
  function checkout(){ alert('Compra de confitería procesada. ¡Disfruta!'); clearCart(); toggleCart(); }
</script>

---

## 10. **search.php** — Buscador de películas y actores

```php
<?php
require 'includes/connection.php';
?>
<?php include 'includes/header.php'; ?>
<main class="container mx-auto px-4 py-8">
  <h2 class="text-2xl font-bold mb-4 text-red-400">Buscador</h2>
  <form method="GET" action="search.php" class="mb-6">
    <div class="flex space-x-2">
      <input type="text" name="q" placeholder="Ingrese nombre de película o actor" class="w-full p-2 rounded bg-gray-700 text-white" required>
      <button type="submit" class="bg-red-600 py-2 px-4 rounded hover:bg-red-700">Buscar</button>
    </div>
  </form>
  <?php if(isset($_GET['q'])):
    $q = '%'.$_GET['q'].'%';
    $stmt = $pdo->prepare("SELECT * FROM peliculas WHERE titulo LIKE ? OR actor_principal LIKE ?");
    $stmt->execute([$q,$q]);
    $results = $stmt->fetchAll();
    if($results): ?>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <?php foreach($results as $r): ?>
        <div class="bg-gray-800 p-4 rounded-lg shadow-md">
          <h3 class="text-xl font-semibold mb-2"><?= htmlspecialchars($r['titulo']) ?></h3>
          <p class="text-gray-400 mb-2">Actor: <?= htmlspecialchars($r['actor_principal']) ?></p>
          <?php if($r['imagen']): ?>
            <img src="<?= $r['imagen'] ?>" alt="<?= htmlspecialchars($r['titulo']) ?>" class="w-full h-48 object-cover rounded mb-2">
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p class="text-gray-300">No se encontraron resultados para "<?= htmlspecialchars($_GET['q']) ?>".</p>
    <?php endif; ?>
  <?php endif; ?>
</main>
<?php include 'includes/footer.php'; ?>