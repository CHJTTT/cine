<?php
// Este archivo header_comun.php NO necesita 'connection.php' directamente,
// a menos que el header mismo necesite datos de la BD (lo cual no parece ser el caso).
// La conexión la requerirá cada página principal (index.php, socios.php, etc.)
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CINESTAR</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="styles.css"> <!-- Asumiendo que styles.css está en la misma carpeta cine_star -->
</head>
<body class="bg-gray-900 text-white font-inter">
<header class="bg-black py-4 shadow-md sticky top-0 z-10">
  <div class="container mx-auto px-4 flex justify-between items-center">
    <a href="index.php" class="flex items-center">
      <img src="assets/images/logo.png" alt="CINESTAR" class="h-12 mr-2"> <!-- Asumiendo que assets/ está dentro de cine_star -->
      <span class="text-white text-2xl font-bold">CINESTAR</span>
    </a>
    <nav class="hidden md:flex space-x-6">
      <a href="index.php" class="hover:text-red-400 transition">Inicio</a>
      <a href="index.php#estrenos" class="hover:text-red-400 transition">Estrenos</a>
      <a href="socios.php" class="hover:text-red-400 transition">Socios</a>
      <a href="compra.php" class="hover:text-red-400 transition">Boletos</a>
      <a href="confiteria.php" class="hover:text-red-400 transition">Confitería</a>
      <a href="search.php" class="hover:text-red-400 transition flex items-center">
        <svg class="h-5 w-5 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.9 14.32a8 8 0 111.414-1.414l4.387 4.386a1 1 0 01-1.414 1.415l-4.387-4.387zM10 16a6 6 0 100-12 6 6 0 000 12z" clip-rule="evenodd"/></svg>
        Buscar
      </a>
    </nav>
    <button id="menu-button" class="md:hidden text-white focus:outline-none" aria-label="Menú">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="white" class="h-6 w-6"><path stroke-linecap="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
    </button>
  </div>
</header>

<!-- Menú móvil -->
<div id="mobile-menu" class="hidden fixed inset-0 bg-gray-900 bg-opacity-90 z-20">
  <div class="bg-gray-800 w-64 h-full p-6 ml-auto">
    <button id="close-menu-button" class="text-white mb-6">
      <svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 20 20" class="h-6 w-6"><path fill-rule="evenodd" d="M10 8.586l4.95-4.95a1 1 0 011.414 1.414L11.414 10l4.95 4.95a1 1 0 01-1.414 1.414L10 11.414l-4.95 4.95a1 1 0 01-1.414-1.414L8.586 10 3.636 5.05a1 1 0 011.414-1.414L10 8.586z" clip-rule="evenodd"/></svg>
    </button>
    <nav class="flex flex-col space-y-4">
      <a href="index.php" class="hover:text-red-400">Inicio</a>
      <a href="index.php#estrenos" class="hover:text-red-400">Estrenos</a>
      <a href="socios.php" class="hover:text-red-400">Socios</a>
      <a href="compra.php" class="hover:text-red-400">Boletos</a>
      <a href="confiteria.php" class="hover:text-red-400">Confitería</a>
      <a href="search.php" class="hover:text-red-400">Buscar</a>
    </nav>
  </div>
</div>

<script>
  // Toggle menú móvil
  const menuBtn = document.getElementById('menu-button');
  const closeMenuBtn = document.getElementById('close-menu-button');
  const mobileMenu = document.getElementById('mobile-menu');
  if(menuBtn && closeMenuBtn && mobileMenu) { // Verificación
    menuBtn.addEventListener('click', () => mobileMenu.classList.remove('hidden'));
    closeMenuBtn.addEventListener('click', () => mobileMenu.classList.add('hidden'));
  }
</script>
<?php // NO CIERRES BODY NI HTML AQUÍ ?>