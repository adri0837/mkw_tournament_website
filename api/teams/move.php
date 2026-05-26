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

$data       = json_decode(file_get_contents('php://input'), true);
$team_id    = intval($data['team_id']    ?? 0);
$group_name = trim($data['group_name']   ?? '');
$slot_number = intval($data['slot_number'] ?? 0);

if ($team_id < 1 || !in_array($group_name, ['A','B','C','D']) || $slot_number < 1) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos inválidos']);
    exit;
}

$pdo->prepare('UPDATE teams SET group_name = ?, slot_number = ? WHERE id = ?')
    ->execute([$group_name, $slot_number, $team_id]);

echo json_encode(['success' => true]);