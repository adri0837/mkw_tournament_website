<?php
header('Content-Type: application/json');
require_once '../config/db.php';

$method = $_SERVER['REQUEST_METHOD'];

// ── GET: obtener reacciones de un clip ─────────────────────────
if ($method === 'GET') {
    $clip_id = intval($_GET['clip_id'] ?? 0);
    if ($clip_id < 1) {
        http_response_code(400);
        echo json_encode(['error' => 'clip_id inválido']);
        exit;
    }

    $stmt = $pdo->prepare('
        SELECT cr.emoji, COUNT(*) as total
        FROM clip_reactions cr
        JOIN clip_reaction_order cro 
            ON cro.clip_id = cr.clip_id 
            AND cro.emoji = cr.emoji
        WHERE cr.clip_id = ?
        GROUP BY cr.emoji
        ORDER BY cro.first_seen ASC
    ');
        $stmt->execute([$clip_id]);
        $counts = $stmt->fetchAll();

    echo json_encode(['reactions' => $counts]);
    exit;
}

// ── POST: agregar una reacción ─────────────────────────────────
if ($method === 'POST') {
    $data    = json_decode(file_get_contents('php://input'), true);
    $clip_id = intval($data['clip_id'] ?? 0);
    $emoji   = trim($data['emoji']    ?? '');

    if ($clip_id < 1 || empty($emoji) || mb_strlen($emoji) > 20) {
        http_response_code(400);
        echo json_encode(['error' => 'Datos inválidos']);
        exit;
    }

    $pdo->prepare('
        INSERT INTO clip_reactions (clip_id, emoji)
        VALUES (?, ?)
    ')->execute([$clip_id, $emoji]);

    $pdo->prepare('
        INSERT IGNORE INTO clip_reaction_order (clip_id, emoji)
        VALUES (?, ?)
    ')->execute([$clip_id, $emoji]);

    $stmt = $pdo->prepare('
        SELECT COUNT(*) as total FROM clip_reactions
        WHERE clip_id = ? AND emoji = ?
    ');
    $stmt->execute([$clip_id, $emoji]);
    $total = $stmt->fetch()['total'];

    echo json_encode(['success' => true, 'action' => 'added', 'total' => $total]);
    exit;
}

// ── DELETE: quitar una reacción ────────────────────────────────
if ($method === 'DELETE') {
    $data    = json_decode(file_get_contents('php://input'), true);
    $clip_id = intval($data['clip_id'] ?? 0);
    $emoji   = trim($data['emoji']    ?? '');

    if ($clip_id < 1 || empty($emoji) || mb_strlen($emoji) > 20) {
        http_response_code(400);
        echo json_encode(['error' => 'Datos inválidos']);
        exit;
    }

    $pdo->prepare('
        DELETE FROM clip_reactions
        WHERE id = (
            SELECT id FROM (
                SELECT id FROM clip_reactions
                WHERE clip_id = ? AND emoji = ?
                ORDER BY created_at ASC
                LIMIT 1
            ) AS tmp
        )
    ')->execute([$clip_id, $emoji]);

    $stmt_check = $pdo->prepare('
        SELECT COUNT(*) as total FROM clip_reactions
        WHERE clip_id = ? AND emoji = ?
    ');
    $stmt_check->execute([$clip_id, $emoji]);
    $remaining = $stmt_check->fetch()['total'];

    if ($remaining == 0) {
        $pdo->prepare('
            DELETE FROM clip_reaction_order
            WHERE clip_id = ? AND emoji = ?
        ')->execute([$clip_id, $emoji]);
    }

    $stmt = $pdo->prepare('
        SELECT COUNT(*) as total FROM clip_reactions
        WHERE clip_id = ? AND emoji = ?
    ');
    $stmt->execute([$clip_id, $emoji]);
    $total = $stmt->fetch()['total'];

    echo json_encode(['success' => true, 'action' => 'removed', 'total' => $total]);
    exit;
}

http_response_code(405);
echo json_encode(['error' => 'Método no permitido']);