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

$id      = intval($_POST['id']   ?? 0);
$name    = trim($_POST['name']   ?? '');
$players = json_decode($_POST['players'] ?? '[]', true);

if ($id < 1 || empty($name)) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos incompletos']);
    exit;
}

if (count($players) < 6) {
    http_response_code(400);
    echo json_encode(['error' => 'Mínimo 6 jugadores requeridos']);
    exit;
}

// Actualizar logo si se subió uno nuevo
$stmt = $pdo->prepare('SELECT logo_path FROM teams WHERE id = ?');
$stmt->execute([$id]);
$team = $stmt->fetch();

$logo_path = $team['logo_path'];

if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
    $ext     = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
    $allowed = ['jpg','jpeg','png','webp'];
    if (in_array(strtolower($ext), $allowed)) {
        // Borrar logo anterior
        if ($logo_path) {
            $old = __DIR__ . '/../../' . $logo_path;
            if (file_exists($old)) unlink($old);
        }
        $filename  = uniqid('logo_') . '.' . $ext;
        $dest      = __DIR__ . '/../../assets/logos-equipos/' . $filename;
        if (move_uploaded_file($_FILES['logo']['tmp_name'], $dest)) {
            $logo_path = 'assets/logos-equipos/' . $filename;
        }
    }
}

// Actualizar equipo
$pdo->prepare('UPDATE teams SET name = ?, logo_path = ? WHERE id = ?')
    ->execute([$name, $logo_path, $id]);

// Reemplazar jugadores
$pdo->prepare('DELETE FROM players WHERE team_id = ?')->execute([$id]);

$stmt = $pdo->prepare('INSERT INTO players (team_id, name, character_icon) VALUES (?, ?, ?)');
foreach ($players as $player) {
    $pname = trim($player['name'] ?? '');
    $picon = trim($player['character_icon'] ?? '');
    if (!empty($pname)) {
        $stmt->execute([$id, $pname, $picon]);
    }
}

echo json_encode(['success' => true]);