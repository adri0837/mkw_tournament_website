<?php
header('Content-Type: application/json');
require_once '../config/db.php';

$stmt = $pdo->query('
    SELECT 
        t.id,
        t.name,
        t.logo_path,
        t.group_name,
        t.slot_number,
        p.id as player_id,
        p.name as player_name,
        p.character_icon
    FROM teams t
    LEFT JOIN players p ON p.team_id = t.id
    ORDER BY t.group_name, t.slot_number, p.id
');

$rows = $stmt->fetchAll();

$teams = [];
foreach ($rows as $row) {
    $tid = $row['id'];
    if (!isset($teams[$tid])) {
        $teams[$tid] = [
            'id'          => $row['id'],
            'name'        => $row['name'],
            'logo_path'   => $row['logo_path'],
            'group_name'  => $row['group_name'],
            'slot_number' => $row['slot_number'],
            'players'     => []
        ];
    }
    if ($row['player_id']) {
        $teams[$tid]['players'][] = [
            'id'             => $row['player_id'],
            'name'           => $row['player_name'],
            'character_icon' => $row['character_icon']
        ];
    }
}

echo json_encode(array_values($teams));