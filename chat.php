<?php
/**
 * chat.php — FAQ-Constrained AI Chatbot Backend
 * 
 * Receives a user message via POST AJAX, searches the FAQs table
 * using keyword matching, and returns a JSON response.
 * 
 * CONSTRAINT: Answers are strictly limited to FAQ data.
 * If no FAQ matches, a safe fallback response is returned.
 * No external knowledge is introduced.
 */
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

header('Content-Type: application/json');

$message = trim($_POST['message'] ?? '');

if (!$message) {
    echo json_encode(['answer' => 'Please type a question and I will do my best to help!']);
    exit;
}

// ── Keyword-based FAQ matching ─────────────────────────────────
// Split message into words, search question and answer columns
$words  = preg_split('/\s+/', strtolower($message));
$where  = [];
$params = [];

foreach ($words as $i => $word) {
    if (strlen($word) >= 3) { // Ignore very short words
        $key = ":w$i";
        $where[]     = "(LOWER(question) LIKE $key OR LOWER(answer) LIKE $key)";
        $params[$key] = "%$word%";
    }
}

$answer = null;

if ($where) {
    $sql  = "SELECT question, answer FROM faqs WHERE " . implode(' OR ', $where);
    $sql .= " ORDER BY (";
    // Score: more matched words = higher priority
    foreach (array_keys($params) as $key) {
        $sql .= "(LOWER(question) LIKE $key) + (LOWER(answer) LIKE $key) + ";
    }
    $sql  = rtrim($sql, '+ ') . ") DESC LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $faq  = $stmt->fetch();

    if ($faq) {
        $answer = "Based on our FAQ:\n\n{$faq['answer']}";
    }
}

// ── Safe fallback if no FAQ matches ───────────────────────────
if (!$answer) {
    $answer = "I can only answer questions based on our FAQ. I couldn't find an exact match for your question. Please visit our FAQ page or contact us at support@snacksonline.com for further help.";
}

echo json_encode(['answer' => $answer]);
