<?php
require_once __DIR__ . '/../includes/db.php';   // provides both $pdo and $lightrag
require_once __DIR__ . '/../includes/auth.php';
require 'vendor/autoload.php';

require_admin();

$faq_id = (int)($_GET['id'] ?? 0);
$stmt   = $pdo->prepare("SELECT question FROM faqs WHERE faq_id = ?");
$stmt->execute([$faq_id]);
$faq    = $stmt->fetch();

if (!$faq) {
    set_flash("FAQ not found.", 'error');
} else {
    // Delete from MySQL
    $pdo->prepare("DELETE FROM faqs WHERE faq_id = ?")->execute([$faq_id]);

    // Delete from LightRAG
    if ($lightrag) {
        try {
            $lightrag->delete($faq_id);
        } catch (Exception $e) {
            file_put_contents(__DIR__ . '/../chat_error.log', date('Y-m-d H:i:s') . " - LightRAG delete failed: " . $e->getMessage() . "\n", FILE_APPEND);
        }
    }

    set_flash("FAQ deleted from knowledge base.");
}

redirect('/snacksonline/admin/faqs.php');