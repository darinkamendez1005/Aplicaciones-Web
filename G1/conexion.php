<?php
// conexion.php
$host     = "localhost";
$port     = "8889";             // Puerto de MySQL predeterminado 
$dbname   = "bd_G1";            // Nombre exacto de tu BD en phpMyAdmin
$usuario  = "root";             
$password = "root";             

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $conexion = new PDO($dsn, $usuario, $password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Error de conexión: ' . $e->getMessage()]);
    exit();
}
?>