<?php
/**
 * embed_faqs.php — Embeds FAQs into LightRAG
 */
require_once __DIR__ . '/includes/db.php';

if (!$lightrag) {
    die("❌ LightRAG is not available. Check host setting in db.php.\n");
}

// Health check
$health = $lightrag->health();
if (!$health || ($health['status'] ?? '') !== 'healthy') {
    die("❌ LightRAG health check failed. Is it running?\n");
}
echo "✓ LightRAG is healthy\n\n";

// Fetch FAQs
$stmt = $pdo->query("SELECT faq_id, category, question, answer FROM faqs");
$faqs = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($faqs)) {
    die("⚠ No FAQs found in the database.\n");
}

# echo "Found " . count($faqs) . " FAQs. Embedding...\n\n";
echo "Found " . count($faqs) . " FAQs.\n\n";

// Load doc status from LightRAG storage directly
// Since the /documents API endpoint is buggy, read the JSON file directly
$statusFile = '/mnt/d/Installations/Python/Scripts/LightRAG/rag_storage/kv_store_doc_status.json';
$docStatuses = [];

if (file_exists($statusFile)) {
    $raw = json_decode(file_get_contents($statusFile), true);
    if ($raw) {
        foreach ($raw as $docId => $docData) {
            // Index by content_summary prefix to match against our FAQ text
            $summary = $docData['content_summary'] ?? '';
            $status  = $docData['status'] ?? 'unknown';
            $docStatuses[$summary] = $status;
        }
    }
    echo "Loaded " . count($docStatuses) . " existing doc statuses.\n\n";
} else {
    echo "No existing doc status file found — embedding all FAQs.\n\n";
}

$skipped  = 0;
$embedded = 0;
$failed   = 0;

foreach ($faqs as $faq) {
    $text = "Category: {$faq['category']}\nQuestion: {$faq['question']}\nAnswer: {$faq['answer']}";

    $result = $lightrag->insert([
        'text'        => $text,
        'description' => $faq['question'],
    ]);

    if ($result !== null) {
        echo "✓ Embedded FAQ #{$faq['faq_id']} [{$faq['category']}]\n";
    } else {
        echo "✗ Failed FAQ #{$faq['faq_id']} — check chat_error.log\n";
    }

    usleep(500000);
}

echo "\n✅ Done!\n";
echo "   Embedded: $embedded\n";
echo "   Skipped (already done): $skipped\n";
echo "   Failed: $failed\n";
echo "Check http://172.28.85.61:8080/webui to confirm text_chunks > 0.\n";