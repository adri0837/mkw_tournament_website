<?php
header('Content-Type: application/json');
require_once '../config/db.php';

$method = $_SERVER['REQUEST_METHOD'];

// ── GET: obtener comentarios de un clip ────────────────────────
if ($method === 'GET') {
    $clip_id = intval($_GET['clip_id'] ?? 0);
    if ($clip_id < 1) {
        http_response_code(400);
        echo json_encode(['error' => 'clip_id inválido']);
        exit;
    }

    $stmt = $pdo->prepare('
        SELECT id, commenter_name, commenter_icon, comment_text, created_at
        FROM clip_comments
        WHERE clip_id = ?
        ORDER BY created_at ASC
    ');
    $stmt->execute([$clip_id]);
    echo json_encode($stmt->fetchAll());
    exit;
}

// ── POST: agregar un comentario (público) ──────────────────────
if ($method === 'POST') {
    $data             = json_decode(file_get_contents('php://input'), true);
    $clip_id          = intval($data['clip_id']         ?? 0);
    $commenter_name   = trim($data['commenter_name']    ?? '');
    $commenter_icon   = trim($data['commenter_icon']    ?? '');
    $comment_text     = trim($data['comment_text']      ?? '');

    if ($clip_id < 1) {
        http_response_code(400);
        echo json_encode(['error' => 'clip_id inválido']);
        exit;
    }
    if (empty($commenter_name)) {
        http_response_code(400);
        echo json_encode(['error' => 'El nombre es obligatorio']);
        exit;
    }
    if (empty($comment_text)) {
        http_response_code(400);
        echo json_encode(['error' => 'El comentario no puede estar vacío']);
        exit;
    }
    if (mb_strlen($comment_text) > 500) {
        http_response_code(400);
        echo json_encode(['error' => 'El comentario no puede superar los 500 caracteres']);
        exit;
    }

    // Verificar que el clip exista
    $check = $pdo->prepare('SELECT id FROM clips WHERE id = ?');
    $check->execute([$clip_id]);
    if (!$check->fetch()) {
        http_response_code(404);
        echo json_encode(['error' => 'Clip no encontrado']);
        exit;
    }

    $stmt = $pdo->prepare('
        INSERT INTO clip_comments (clip_id, commenter_name, commenter_icon, comment_text)
        VALUES (?, ?, ?, ?)
    ');
    $stmt->execute([$clip_id, $commenter_name, $commenter_icon ?: null, $comment_text]);

    echo json_encode([
        'success' => true,
        'id'      => $pdo->lastInsertId(),
    ]);
    exit;
}

// ── DELETE: eliminar comentario (solo admin) ───────────────────
if ($method === 'DELETE') {
    session_start();
    if (!isset($_SESSION['admin_id'])) {
        http_response_code(401);
        echo json_encode(['error' => 'No autorizado']);
        exit;
    }

    $data = json_decode(file_get_contents('php://input'), true);
    $id   = intval($data['id'] ?? 0);

    if ($id < 1) {
        http_response_code(400);
        echo json_encode(['error' => 'ID inválido']);
        exit;
    }

    $stmt = $pdo->prepare('SELECT id FROM clip_comments WHERE id = ?');
    $stmt->execute([$id]);
    if (!$stmt->fetch()) {
        http_response_code(404);
        echo json_encode(['error' => 'Comentario no encontrado']);
        exit;
    }

    $pdo->prepare('DELETE FROM clip_comments WHERE id = ?')->execute([$id]);
    echo json_encode(['success' => true]);
    exit;
}

http_response_code(405);
echo json_encode(['error' => 'Método no permitido']);