<?php
// conecta a la base de datos bd_prueba en mysql usando pdo

$host     = "127.0.0.1"; // direccion del servidor local de base de datos
$port     = "3306";      // puerto de mysql en xampp
$dbname   = "bd_prueba"; // nombre exacto de la base de datos en phpmyadmin
$usuario  = "root";      // usuario por defecto de mysql en xampp
$password = "";          // contrasena vacia por defecto en xampp

try {
    // arma la cadena de conexion con host, puerto, base de datos y codificacion utf8mb4
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";

    // crea la conexion pdo
    $conexion = new PDO($dsn, $usuario, $password);

    // hace que los errores de sql lancen excepciones para atraparlas en el catch
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // devuelve las filas como arreglos asociativos con el nombre de cada columna
    $conexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // si falla la conexion, responde en json y corta la ejecucion
    header('Content-Type: application/json');
    echo json_encode([
        'status'  => 'error',
        'message' => 'error de conexion: ' . $e->getMessage()
    ]);
    exit();
}
?>