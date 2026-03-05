<?php
/**
 * chat.php — FAQ-Constrained AI Chatbot Backend
 * Simple, working version without complex parameter binding
 */

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $message = trim($_POST['message'] ?? '');
    
    if (empty($message)) {
        echo json_encode(['answer' => 'Please type a question and I will do my best to help!']);
        exit;
    }

    // Sanitize input
    $message = strip_tags($message);
    if (strlen($message) > 500) {
        $message = substr($message, 0, 500);
    }

    // Extract keywords (3+ chars)
    $words = preg_split('/\s+/', strtolower($message), -1, PREG_SPLIT_NO_EMPTY);
    $words = array_unique($words);
    
    $filteredWords = [];
    foreach ($words as $w) {
        if (strlen($w) >= 3) {
            $filteredWords[] = $w;
        }
    }
    $words = $filteredWords;

    if (empty($words)) {
        echo json_encode(['answer' => 'Please ask a more specific question.']);
        exit;
    }

    // Search FAQs - try each keyword and find best match
    $bestFaq = null;
    $bestScore = 0;

    foreach ($words as $word) {
        $searchTerm = '%' . $word . '%';
        
        // Search in question first (higher priority), then answer
        $sql = "SELECT question, answer FROM faqs 
                WHERE question LIKE ? OR answer LIKE ?
                LIMIT 5";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$searchTerm, $searchTerm]);
        $faqs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Score each result - questions match higher than answers
        foreach ($faqs as $faq) {
            $score = 0;
            if (stripos($faq['question'], $word) !== false) {
                $score += 2; // Question match is worth 2 points
            }
            if (stripos($faq['answer'], $word) !== false) {
                $score += 1; // Answer match is worth 1 point
            }
            
            // Keep the best match
            if ($score > $bestScore) {
                $bestScore = $score;
                $bestFaq = $faq;
            }
        }
    }

    // Return best match or fallback
    if ($bestFaq && !empty($bestFaq['answer'])) {
        echo json_encode([
            'answer' => trim($bestFaq['answer']),
            'source' => 'FAQ'
        ]);
    } else {
        echo json_encode([
            'answer' => "I can only answer questions based on our FAQ. I couldn't find a match for your question.\n\nPlease visit our FAQ page or contact support@snacksonline.com",
            'source' => 'fallback'
        ]);
    }

} catch (Exception $e) {
    file_put_contents(__DIR__ . '/chat_error.log', date('Y-m-d H:i:s') . " - " . $e->getMessage() . "\n", FILE_APPEND);
    echo json_encode([
        'answer' => 'Sorry, there was an error. Please try again.',
        'debug' => $e->getMessage()
    ]);
}