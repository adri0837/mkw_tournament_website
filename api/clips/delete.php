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

$stmt = $pdo->prepare('SELECT file_path FROM clips WHERE id = ?');
$stmt->execute([$id]);
$clip = $stmt->fetch();

if (!$clip) {
    http_response_code(404);
    echo json_encode(['error' => 'Clip no encontrado']);
    exit;
}

// Borrar archivo físico del servidor
$full_path = __DIR__ . '/../../' . $clip['file_path'];
if (file_exists($full_path)) {
    unlink($full_path);
}

// Borrar registro de la BD
$pdo->prepare('DELETE FROM clips WHERE id = ?')->execute([$id]);

echo json_encode(['success' => true]);