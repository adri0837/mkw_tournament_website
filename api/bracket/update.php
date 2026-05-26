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

$data  = json_decode(file_get_contents('php://input'), true);
$slots = $data['slots'] ?? [];

if (empty($slots)) {
    http_response_code(400);
    echo json_encode(['error' => 'No se recibieron slots']);
    exit;
}

$stmt = $pdo->prepare('
    UPDATE bracket_slots
    SET team_id = ?
    WHERE round = ? AND slot_number = ?
');

foreach ($slots as $slot) {
    $round       = $slot['round']       ?? '';
    $slot_number = $slot['slot_number'] ?? 0;
    $team_id     = !empty($slot['team_id']) ? intval($slot['team_id']) : null;
    if ($round && $slot_number) {
        $stmt->execute([$team_id, $round, $slot_number]);
    }
}

echo json_encode(['success' => true]);