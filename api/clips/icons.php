<?php
header('Content-Type: application/json');

$icons_dir = __DIR__ . '/../../assets/character-icons/';
$allowed   = ['png', 'jpg', 'jpeg', 'webp'];

$icons = array_values(array_filter(
    scandir($icons_dir),
    function($file) use ($icons_dir, $allowed) {
        if (in_array($file, ['.', '..'])) return false;
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        return in_array($ext, $allowed) && is_file($icons_dir . $file);
    }
));

echo json_encode($icons);