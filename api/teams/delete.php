<?php
session_start();
header('Content-Type: application/json');
require_once '../config/db.php';

if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$id   = intval($data['id'] ?? 0);

if ($id < 1) {
    http_response_code(400);
    echo json_encode(['error' => 'ID inválido']);
    exit;
}

// Obtener logo para borrarlo del disco
$stmt = $pdo->prepare('SELECT logo_path FROM teams WHERE id = ?');
$stmt->execute([$id]);
$team = $stmt->fetch();

if (!$team) {
    http_response_code(404);
    echo json_encode(['error' => 'Equipo no encontrado']);
    exit;
}

// Borrar logo físico
if ($team['logo_path']) {
    $full_path = __DIR__ . '/../../' . $team['logo_path'];
    if (file_exists($full_path)) {
        unlink($full_path);
    }
}

// Borrar equipo (jugadores y standings se borran en cascada por FOREIGN KEY)
$pdo->prepare('DELETE FROM teams WHERE id = ?')->execute([$id]);

echo json_encode(['success' => true]);