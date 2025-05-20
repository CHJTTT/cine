<?php
// connection.php no parece ser necesario para la lógica actual de este archivo,
// ya que los combos están hardcodeados y el carrito es solo JS.
// Si en el futuro los combos vienen de la BD, entonces sí necesitarías:
// require 'connection.php';
include 'header_comun.php'; // CORREGIDO - Asume que has creado cine_star/header_comun.php
?>
<main class="container mx-auto px-4 py-8">
  <h2 class="text-2xl font-bold mb-4 text-red-400">Confitería</h2>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php
    // Estos combos podrían venir de la tabla confiteria_productos si la implementas
    $combos = [
      ['id'=>1,'titulo'=>'Combo Clásico','desc'=>'Palomitas grandes + 2 bebidas','precio'=>25.00,'img'=>'assets/images/combo1.jpg'], // CORREGIDA RUTA IMAGEN
      ['id'=>2,'titulo'=>'Combo Dulce','desc'=>'Palomitas acarameladas + 2 bebidas','precio'=>27.00,'img'=>'assets/images/combo2.jpg'], // CORREGIDA RUTA IMAGEN
      ['id'=>3,'titulo'=>'Combo Nachos','desc'=>'Nachos con queso + 2 bebidas','precio'=>30.00,'img'=>'assets/images/combo3.jpg'], // CORREGIDA RUTA IMAGEN
    ];
    foreach($combos as $combo): ?>
    <div class="bg-gray-800 p-6 rounded-lg shadow-md flex flex-col">
      <img src="<?= htmlspecialchars($combo['img']) ?>" alt="<?= htmlspecialchars($combo['titulo']) ?>" class="rounded mb-4 h-48 object-cover">
      <h3 class="text-xl font-semibold mb-2"><?= htmlspecialchars($combo['titulo']) ?></h3>
      <p class="text-gray-300 mb-4"><?= htmlspecialchars($combo['desc']) ?></p>
      <p class="text-red-500 font-bold text-lg mb-4">S/ <?= number_format($combo['precio'],2) ?></p>
      <button onclick="addCart(<?= htmlspecialchars(json_encode($combo)) ?>)" class="mt-auto bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700">Agregar al Carrito</button>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Carrito modal -->
  <div id="cartModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-30"> <!-- Aumentado z-index -->
    <div class="bg-gray-800 p-6 rounded-lg shadow-md w-full max-w-md mx-4"> <!-- Ajustado ancho y margen -->
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-xl font-semibold text-red-400">Tu Carrito</h3>
        <button onclick="toggleCart()" class="text-gray-400 hover:text-white text-2xl leading-none">×</button> <!-- Botón de cierre mejorado -->
      </div>
      <ul id="cartList" class="space-y-2 text-gray-300 max-h-60 overflow-y-auto mb-4"></ul> <!-- Scroll para lista larga -->
      <p class="mt-4 font-semibold text-gray-200">Total: S/ <span id="cartTotal">0.00</span></p>
      <div class="mt-6 flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-4">
        <button onclick="clearCart()" class="w-full sm:w-auto bg-gray-600 text-white py-2 px-4 rounded hover:bg-gray-500">Vaciar Carrito</button>
        <button onclick="checkout()" class="w-full sm:w-auto bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700">Pagar</button>
      </div>
    </div>
  </div>
</main>
<?php include 'piedepaginacomun.php'; // CORREGIDO - Asume que has creado cine_star/piedepaginacomun.php ?>

<script>
  let cart = [];
  const combosData = <?= json_encode(array_column($combos, null, 'id')) ?>; // Pasar datos de combos a JS

  function addCart(comboObject){ // Recibir el objeto combo completo
    // Si deseas permitir múltiples unidades del mismo combo, necesitarías una lógica más compleja aquí
    // Por ahora, simplemente añade el combo si no está o podrías aumentar cantidad
    cart.push({id: comboObject.id, titulo: comboObject.titulo, precio: comboObject.precio});
    updateCartUI();
    toggleCart(true); // Abrir carrito
  }

  function updateCartUI(){
    const cartListEl = document.getElementById('cartList');
    const cartTotalEl = document.getElementById('cartTotal');
    if (!cartListEl || !cartTotalEl) return;

    cartListEl.innerHTML = ''; // Limpiar lista
    let currentTotal = 0;

    cart.forEach((item, index) => {
      currentTotal += item.precio;
      const listItem = document.createElement('li');
      listItem.className = 'flex justify-between items-center';
      listItem.innerHTML = `
        <span>${htmlspecialchars(item.titulo)} - S/ ${item.precio.toFixed(2)}</span>
        <button onclick="removeFromCart(${index})" class="text-red-400 hover:text-red-600 text-xs">Quitar</button>
      `;
      cartListEl.appendChild(listItem);
    });
    cartTotalEl.textContent = currentTotal.toFixed(2);
  }

  function removeFromCart(index) {
    cart.splice(index, 1); // Remover item por índice
    updateCartUI();
  }

  function toggleCart(forceOpen = null) {
    const cartModalEl = document.getElementById('cartModal');
    if (!cartModalEl) return;
    if (forceOpen === true) {
        cartModalEl.classList.remove('hidden');
        cartModalEl.classList.add('flex'); // Usar flex para centrar
    } else if (forceOpen === false) {
        cartModalEl.classList.add('hidden');
        cartModalEl.classList.remove('flex');
    } else {
        cartModalEl.classList.toggle('hidden');
        cartModalEl.classList.toggle('flex');
    }
  }

  function clearCart(){
    cart = [];
    updateCartUI();
    // toggleCart(false); // Opcionalmente cerrar carrito al vaciar
  }

  function checkout(){
    if (cart.length === 0) {
        alert('Tu carrito está vacío.');
        return;
    }
    alert('Compra de confitería procesada por un total de S/ ' + document.getElementById('cartTotal').textContent + '. ¡Disfruta!');
    clearCart();
    toggleCart(false); // Cerrar carrito después de "pagar"
  }

  // Función simple para escapar HTML en JS (si no usas una librería)
  function htmlspecialchars(str) {
    if (typeof str !== 'string') return '';
    return str.replace(/[&<>"']/g, function (match) {
      const_escape = {
        '&': '&',
        '<': '<',
        '>': '>',
        '"': '"',
        "'": '''
      };
      return const_escape[match];
    });
  }
</script>