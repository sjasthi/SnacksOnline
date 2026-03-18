<?php
require_once __DIR__ . '/../includes/db.php';   // now provides both $pdo and $lightrag
require_once __DIR__ . '/../includes/auth.php';
require 'vendor/autoload.php';

require_admin();

use OpenAI\Client;

$openai = OpenAI::client('YOUR_OPENAI_API_KEY');

$faq    = null;
$errors = [];
$faq_id = (int)($_GET['id'] ?? 0);
$action = $faq_id ? 'edit' : 'create';

if ($action === 'edit') {
    $stmt = $pdo->prepare("SELECT * FROM faqs WHERE faq_id = ?");
    $stmt->execute([$faq_id]);
    $faq  = $stmt->fetch();
    if (!$faq) {
        set_flash("FAQ not found.", 'error');
        redirect('/snacksonline/admin/faqs.php');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = trim($_POST['category'] ?? '');
    $question = trim($_POST['question'] ?? '');
    $answer   = trim($_POST['answer']   ?? '');

    if (!$category) $errors[] = "Category is required.";
    if (!$question) $errors[] = "Question is required.";
    if (!$answer)   $errors[] = "Answer is required.";

    if (!$errors) {
        if ($action === 'create') {
            $pdo->prepare("INSERT INTO faqs (category, question, answer) VALUES (?,?,?)")
                ->execute([$category, $question, $answer]);
            $faq_id = $pdo->lastInsertId();
            set_flash("FAQ added to knowledge base!");
        } else {
            $pdo->prepare("UPDATE faqs SET category=?, question=?, answer=? WHERE faq_id=?")
                ->execute([$category, $question, $answer, $faq_id]);
            set_flash("FAQ updated successfully!");
        }

        // Generate embedding and sync with LightRAG
        try {
            $text = $question . " " . $answer;
            $response = $openai->embeddings()->create([
                'model' => 'text-embedding-3-small',
                'input' => $text,
            ]);
            $embedding = $response['data'][0]['embedding'];

            if ($action === 'create') {
                $lightrag->insert([
                    'id' => $faq_id,
                    'embedding' => $embedding,
                    'metadata' => [
                        'category' => $category,
                        'question' => $question,
                        'answer'   => $answer
                    ]
                ]);
            } else {
                $lightrag->update($faq_id, [
                    'embedding' => $embedding,
                    'metadata' => [
                        'category' => $category,
                        'question' => $question,
                        'answer'   => $answer
                    ]
                ]);
            }
        } catch (Exception $e) {
            file_put_contents(__DIR__ . '/../chat_error.log', date('Y-m-d H:i:s') . " - Embedding sync failed: " . $e->getMessage() . "\n", FILE_APPEND);
        }

        redirect('/snacksonline/admin/faqs.php');
    }
}

// Categories for dropdown
$categories = ['Ordering', 'Payments', 'Shipping', 'Returns', 'Technical'];
$page_title  = $action === 'edit' ? 'Edit FAQ' : 'Add New FAQ';

require_once __DIR__ . '/../includes/admin_navbar.php';
?>