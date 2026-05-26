<?php
session_start();
header('Content-Type: application/json');
require_once '../config/db.php';

if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

$data     = json_decode(file_get_contents('php://input'), true);
$team_id  = intval($data['team_id'] ?? 0);
$slot_key = trim($data['slot_key'] ?? '');

if ($team_id < 1 || empty($slot_key)) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos inválidos']);
    exit;
}

// Verificar que el equipo existe
$check = $pdo->prepare('SELECT id FROM teams WHERE id = ?');
$check->execute([$team_id]);
if (!$check->fetch()) {
    http_response_code(404);
    echo json_encode(['error' => 'Equipo no encontrado']);
    exit;
}

// Insertar el movimiento
$pdo->prepare('
    INSERT INTO bracket_progress (team_id, slot_key)
    VALUES (?, ?)
')->execute([$team_id, $slot_key]);

echo json_encode(['success' => true]);