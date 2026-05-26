<?php
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['admin_id'])) {
    echo json_encode([
        'logged_in' => true,
        'username'  => $_SESSION['admin_user']
    ]);
} else {
    echo json_encode(['logged_in' => false]);
}