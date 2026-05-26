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

$name       = trim($_POST['name'] ?? '');
$group_name = trim($_POST['group_name'] ?? '');
$slot       = intval($_POST['slot_number'] ?? 0);
$players    = json_decode($_POST['players'] ?? '[]', true);

if (empty($name) || empty($group_name) || $slot < 1) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos incompletos']);
    exit;
}

if (!in_array($group_name, ['A','B','C','D'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Grupo inválido']);
    exit;
}

if (count($players) < 6) {
    http_response_code(400);
    echo json_encode(['error' => 'Mínimo 6 jugadores requeridos']);
    exit;
}

// Subir logo
$logo_path = null;
if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
    $ext       = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
    $allowed   = ['jpg', 'jpeg', 'png', 'webp'];
    if (!in_array(strtolower($ext), $allowed)) {
        http_response_code(400);
        echo json_encode(['error' => 'Formato de imagen no permitido']);
        exit;
    }
    $filename  = uniqid('logo_') . '.' . $ext;
    $dest      = __DIR__ . '/../../assets/logos-equipos/' . $filename;
    if (move_uploaded_file($_FILES['logo']['tmp_name'], $dest)) {
        $logo_path = 'assets/logos-equipos/' . $filename;
    }
}

// Insertar equipo
$stmt = $pdo->prepare('
    INSERT INTO teams (name, logo_path, group_name, slot_number)
    VALUES (?, ?, ?, ?)
');
$stmt->execute([$name, $logo_path, $group_name, $slot]);
$team_id = $pdo->lastInsertId();

// Insertar jugadores
$stmt = $pdo->prepare('
    INSERT INTO players (team_id, name, character_icon)
    VALUES (?, ?, ?)
');
foreach ($players as $player) {
    $pname = trim($player['name'] ?? '');
    $picon = trim($player['character_icon'] ?? '');
    if (!empty($pname)) {
        $stmt->execute([$team_id, $pname, $picon]);
    }
}

// Insertar fila en standings automáticamente
$pdo->prepare('INSERT INTO standings (team_id) VALUES (?)')->execute([$team_id]);

echo json_encode(['success' => true, 'team_id' => $team_id]);