<?php
header('Content-Type: application/json');
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

$player_name     = trim($_POST['player_name']     ?? '');
$character_icon  = trim($_POST['character_icon']  ?? '');

if (empty($player_name)) {
    http_response_code(400);
    echo json_encode(['error' => 'Nombre de jugador requerido']);
    exit;
}

if (!isset($_FILES['clip']) || $_FILES['clip']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(['error' => 'Archivo de clip requerido']);
    exit;
}

// Validar tipo de archivo
$finfo    = new finfo(FILEINFO_MIME_TYPE);
$mime     = $finfo->file($_FILES['clip']['tmp_name']);
$allowed  = ['video/mp4'];

if (!in_array($mime, $allowed)) {
    http_response_code(400);
    echo json_encode(['error' => 'Solo se permiten archivos MP4']);
    exit;
}

// Límite de tamaño: 150MB
$max_size = 150 * 1024 * 1024;
if ($_FILES['clip']['size'] > $max_size) {
    http_response_code(400);
    echo json_encode(['error' => 'El clip no puede pesar más de 150MB']);
    exit;
}

$filename = uniqid('clip_') . '.mp4';
$dest     = __DIR__ . '/../../uploads/clips/' . $filename;

if (!move_uploaded_file($_FILES['clip']['tmp_name'], $dest)) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al guardar el clip']);
    exit;
}

$stmt = $pdo->prepare('
    INSERT INTO clips (player_name, character_icon, file_path)
    VALUES (?, ?, ?)
');
$stmt->execute([$player_name, $character_icon, 'uploads/clips/' . $filename]);

echo json_encode(['success' => true]);