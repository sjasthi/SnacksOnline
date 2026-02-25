<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_admin();

$item_id = trim($_GET['id'] ?? '');
$stmt    = $pdo->prepare("SELECT * FROM items WHERE item_id = ?");
$stmt->execute([$item_id]);
$item    = $stmt->fetch();

if (!$item) { set_flash("Item not found.", 'error'); redirect('/snacksonline/admin/items.php'); }

// Delete image file
if ($item['image'] && file_exists(__DIR__.'/../assets/uploads/'.$item['image'])) {
    unlink(__DIR__.'/../assets/uploads/'.$item['image']);
}

$pdo->prepare("DELETE FROM items WHERE item_id = ?")->execute([$item_id]);
set_flash("'{$item['name']}' removed from the catalogue.");
redirect('/snacksonline/admin/items.php');
