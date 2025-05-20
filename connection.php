<?php
$host = 'localhost'; // O 127.0.0.1
$db   = 'cinestar_db'; // Elige un nombre para tu base de datos
$user = 'root'; // Usuario por defecto de MySQL en Laragon
$pass = '';     // Contraseña por defecto de MySQL en Laragon (vacía)
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>