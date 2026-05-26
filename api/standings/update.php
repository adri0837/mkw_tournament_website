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

$data    = json_decode(file_get_contents('php://input'), true);
$team_id = intval($data['team_id'] ?? 0);
$points  = intval($data['points']  ?? 0);
$played  = intval($data['played']  ?? 0);
$wins    = intval($data['wins']    ?? 0);
$draws   = intval($data['draws']   ?? 0);
$losses  = intval($data['losses']  ?? 0);

if ($team_id < 1) {
    http_response_code(400);
    echo json_encode(['error' => 'ID de equipo inválido']);
    exit;
}

if ($wins + $draws + $losses !== $played) {
    http_response_code(400);
    echo json_encode(['error' => 'La suma de W + E + L debe ser igual a PJ']);
    exit;
}

$stmt = $pdo->prepare('
    UPDATE standings
    SET points  = ?,
        played  = ?,
        wins    = ?,
        draws   = ?,
        losses  = ?
    WHERE team_id = ?
');
$stmt->execute([$points, $played, $wins, $draws, $losses, $team_id]);

echo json_encode(['success' => true]);