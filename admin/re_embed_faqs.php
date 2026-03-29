<?php
/**
 * reembed_faqs.php — Maintenance script to refresh embeddings for all FAQs
 */

require_once __DIR__ . '/../includes/db.php';   // provides $pdo and $lightrag
require_once __DIR__ . '/../includes/auth.php';
require 'vendor/autoload.php';

require_admin();

use OpenAI\Client;

$openai = OpenAI::client('YOUR_OPENAI_API_KEY');

echo "<h2>Re-embedding FAQs...</h2>";

try {
    // Fetch all FAQs
    $stmt = $pdo->query("SELECT faq_id, category, question, answer FROM faqs");
    $faqs = $stmt->fetchAll();

    foreach ($faqs as $faq) {
        $text = $faq['question'] . " " . $faq['answer'];

        // Generate embedding
        $response = $openai->embeddings()->create([
            'model' => 'text-embedding-3-small',
            'input' => $text,
        ]);
        $embedding = $response['data'][0]['embedding'];

        // Update LightRAG
        try {
            $lightrag->update($faq['faq_id'], [
                'embedding' => $embedding,
                'metadata' => [
                    'category' => $faq['category'],
                    'question' => $faq['question'],
                    'answer'   => $faq['answer']
                ]
            ]);
            echo "<p>✅ FAQ {$faq['faq_id']} re-embedded successfully.</p>";
        } catch (Exception $e) {
            echo "<p>❌ LightRAG update failed for FAQ {$faq['faq_id']}: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }

    echo "<h3>All FAQs processed.</h3>";

} catch (Exception $e) {
    echo "<p style='color:red'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}