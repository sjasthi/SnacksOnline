<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_admin();

$faq_id = (int)($_GET['id'] ?? 0);
$stmt   = $pdo->prepare("SELECT question FROM faqs WHERE faq_id = ?");
$stmt->execute([$faq_id]);
$faq    = $stmt->fetch();

if (!$faq) {
    set_flash("FAQ not found.", 'error');
} else {
    $pdo->prepare("DELETE FROM faqs WHERE faq_id = ?")->execute([$faq_id]);
    set_flash("FAQ deleted from knowledge base.");
}

redirect('/snacksonline/admin/faqs.php');
