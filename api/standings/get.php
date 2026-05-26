<?php
header('Content-Type: application/json');
require_once '../config/db.php';

$stmt = $pdo->query('
    SELECT
        s.id,
        s.points,
        s.played,
        s.wins,
        s.draws,
        s.losses,
        t.id   as team_id,
        t.name as team_name,
        t.logo_path
    FROM standings s
    JOIN teams t ON t.id = s.team_id
    ORDER BY s.points DESC, s.wins DESC, s.played ASC
');

echo json_encode($stmt->fetchAll());