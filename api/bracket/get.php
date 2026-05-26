<?php
header('Content-Type: application/json');
require_once '../config/db.php';

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

echo json_encode($stmt->fetchAll());