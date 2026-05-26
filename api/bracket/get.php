<?php
header('Content-Type: application/json');
require_once '../config/db.php';

// Slots base (posiciones iniciales de grupos)
$stmt = $pdo->query('
    SELECT
        b.id,
        b.round,
        b.slot_number,
        b.group_name,
        b.team_id,
        t.name      as team_name,
        t.logo_path as team_logo
    FROM bracket_slots b
    LEFT JOIN teams t ON t.id = b.team_id
    ORDER BY b.round, b.slot_number
');
$slots = $stmt->fetchAll();

// Progreso (movimientos realizados)
$stmt2 = $pdo->query('
    SELECT
        bp.team_id,
        bp.slot_key,
        bp.moved_at,
        t.name      as team_name,
        t.logo_path as team_logo
    FROM bracket_progress bp
    JOIN teams t ON t.id = bp.team_id
    ORDER BY bp.moved_at ASC
');
$progress = $stmt2->fetchAll();

echo json_encode([
    'slots'    => $slots,
    'progress' => $progress
]);