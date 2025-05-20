<?php
require 'includes/connection.php';
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['dni'])){
  $dni = $_POST['dni'];
  $stmt = $pdo->prepare("DELETE FROM socios WHERE dni = ?");
  $stmt->execute([$dni]);
}
header('Location: socios.php');
exit;