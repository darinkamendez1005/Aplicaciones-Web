<?php
// api_roll.php
header('Content-Type: application/json');
require_once "conexion.php";

$action = $_GET['action'] ?? '';

try {
    if ($action === 'listar') {
        $stmt = $conexion->query("SELECT * FROM roll ORDER BY rol_cve_rol DESC");
        $roles = $stmt->fetchAll();
        echo json_encode(['status' => 'success', 'data' => $roles]);
        exit();
    }

    if ($action === 'guardar') {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
            exit();
        }

        $id     = $_POST['rol_cve_rol'] ?? null;
        $nombre = trim($_POST['rol_nombre'] ?? '');

        if (empty($nombre)) {
            echo json_encode(['status' => 'error', 'message' => 'El nombre del rol es obligatorio']);
            exit();
        }

        if (!empty($id)) {
            $stmt = $conexion->prepare("UPDATE roll SET rol_nombre = :nombre WHERE rol_cve_rol = :id");
            $stmt->execute([':nombre' => $nombre, ':id' => $id]);
            echo json_encode(['status' => 'success', 'message' => 'Rol actualizado correctamente']);
        } else {
            $stmt = $conexion->prepare("INSERT INTO roll (rol_nombre) VALUES (:nombre)");
            $stmt->execute([':nombre' => $nombre]);
            echo json_encode(['status' => 'success', 'message' => 'Rol registrado correctamente']);
        }
        exit();
    }

    if ($action === 'eliminar') {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            echo json_encode(['status' => 'error', 'message' => 'ID no proporcionado']);
            exit();
        }
        $stmt = $conexion->prepare("DELETE FROM roll WHERE rol_cve_rol = :id");
        $stmt->execute([':id' => $id]);
        echo json_encode(['status' => 'success', 'message' => 'Rol eliminado correctamente']);
        exit();
    }

    echo json_encode(['status' => 'error', 'message' => 'Acción no válida']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error SQL: ' . $e->getMessage()]);
}
?>