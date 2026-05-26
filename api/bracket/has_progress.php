<?php
header('Content-Type: application/json');
require_once '../config/db.php';

$stmt = $pdo->query('SELECT COUNT(*) as total FROM bracket_progress');
$row  = $stmt->fetch();

echo json_encode(['has_progress' => $row['total'] > 0]);