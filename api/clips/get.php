<?php
header('Content-Type: application/json');
require_once '../config/db.php';

$stmt = $pdo->query('
    SELECT
        id,
        player_name,
        character_icon,
        file_path,
        uploaded_at
    FROM clips
    ORDER BY uploaded_at DESC
');

echo json_encode($stmt->fetchAll());