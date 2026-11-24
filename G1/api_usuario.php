<?php
// api_usuario.php
header('Content-Type: application/json');
require_once "conexion.php";

$action = $_GET['action'] ?? '';

try {
    if ($action === 'obtener_roles') {
        $stmt = $conexion->query("SELECT rol_cve_rol, rol_nombre FROM roll ORDER BY rol_nombre ASC");
        $roles = $stmt->fetchAll();
        echo json_encode(['status' => 'success', 'data' => $roles]);
        exit();
    }

    if ($action === 'listar') {
        $sql = "SELECT u.usu_cve_usuario, u.usu_nombre, u.usu_apaterno, u.usu_amaterno, u.usu_correo, u.usu_pass, u.rol_cve_rol, u.usu_activo, r.rol_nombre 
                FROM usuarios u 
                INNER JOIN roll r ON u.rol_cve_rol = r.rol_cve_rol 
                ORDER BY u.usu_cve_usuario DESC";
        $stmt = $conexion->query($sql);
        $usuarios = $stmt->fetchAll();
        echo json_encode(['status' => 'success', 'data' => $usuarios]);
        exit();
    }

    if ($action === 'obtener') {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            echo json_encode(['status' => 'error', 'message' => 'ID no proporcionado']);
            exit();
        }
        $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE usu_cve_usuario = :id");
        $stmt->execute([':id' => $id]);
        $usuario = $stmt->fetch();
        if ($usuario) {
            echo json_encode(['status' => 'success', 'data' => $usuario]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Usuario no encontrado']);
        }
        exit();
    }

    if ($action === 'guardar') {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
            exit();
        }

        $id       = $_POST['usu_cve_usuario'] ?? null;
        $nombre   = trim($_POST['usu_nombre'] ?? '');
        $apaterno = trim($_POST['usu_apaterno'] ?? '');
        $amaterno = trim($_POST['usu_amaterno'] ?? '');
        $correo   = trim($_POST['usu_correo'] ?? '');
        $pass     = trim($_POST['usu_pass'] ?? '');
        $rol      = $_POST['rol_cve_rol'] ?? '';
        $activo   = isset($_POST['usu_activo']) ? (int)$_POST['usu_activo'] : 1;

        if (empty($nombre) || empty($apaterno) || empty($correo) || empty($rol) || empty($pass)) {
            echo json_encode(['status' => 'error', 'message' => 'Todos los campos obligatorios deben completarse']);
            exit();
        }

        if (!empty($id)) {
            // Actualizar
            $sql = "UPDATE usuarios 
                    SET usu_nombre = :nombre, usu_apaterno = :apaterno, usu_amaterno = :amaterno, 
                        usu_correo = :correo, usu_pass = :pass, rol_cve_rol = :rol, usu_activo = :activo 
                    WHERE usu_cve_usuario = :id";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([
                ':nombre'   => $nombre,
                ':apaterno' => $apaterno,
                ':amaterno' => $amaterno,
                ':correo'   => $correo,
                ':pass'     => $pass,
                ':rol'      => $rol,
                ':activo'   => $activo,
                ':id'       => $id
            ]);
            echo json_encode(['status' => 'success', 'message' => 'Usuario actualizado correctamente']);
        } else {
            // Insertar
            $sql = "INSERT INTO usuarios (usu_nombre, usu_apaterno, usu_amaterno, usu_correo, usu_pass, rol_cve_rol, usu_activo) 
                    VALUES (:nombre, :apaterno, :amaterno, :correo, :pass, :rol, :activo)";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([
                ':nombre'   => $nombre,
                ':apaterno' => $apaterno,
                ':amaterno' => $amaterno,
                ':correo'   => $correo,
                ':pass'     => $pass,
                ':rol'      => $rol,
                ':activo'   => $activo
            ]);
            echo json_encode(['status' => 'success', 'message' => 'Usuario registrado correctamente']);
        }
        exit();
    }

    if ($action === 'eliminar') {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            echo json_encode(['status' => 'error', 'message' => 'ID no proporcionado']);
            exit();
        }
        $stmt = $conexion->prepare("DELETE FROM usuarios WHERE usu_cve_usuario = :id");
        $stmt->execute([':id' => $id]);
        echo json_encode(['status' => 'success', 'message' => 'Usuario eliminado correctamente']);
        exit();
    }

    echo json_encode(['status' => 'error', 'message' => 'Acción no válida']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error SQL: ' . $e->getMessage()]);
}
?>