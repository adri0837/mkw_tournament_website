<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$username = trim($data['username'] ?? '');
$password = trim($data['password'] ?? '');

if (empty($username) || empty($password)) {
    http_response_code(400);
    echo json_encode(['error' => 'Usuario y contraseña requeridos']);
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM admins WHERE username = ?');
$stmt->execute([$username]);
$admin = $stmt->fetch();

if (!$admin || !password_verify($password, $admin['password_hash'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Credenciales incorrectas']);
    exit;
}

$_SESSION['admin_id']   = $admin['id'];
$_SESSION['admin_user'] = $admin['username'];

echo json_encode([
    'success'  => true,
    'username' => $admin['username']
]);