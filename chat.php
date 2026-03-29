<?php
/**
 * chat.php — FAQ Chatbot using LightRAG for retrieval + OpenAI for response
 */

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require 'vendor/autoload.php';

use OpenAI\Client;

$openai = OpenAI::factory()
    ->withApiKey('')
    ->withBaseUri('https://api.groq.com/openai/v1')
    ->make();


header('Content-Type: application/json; charset=utf-8');

try {
    $message = trim($_POST['message'] ?? '');

    if (empty($message)) {
        echo json_encode(['answer' => 'Please type a question and I will do my best to help!']);
        exit;
    }

    $message = strip_tags($message);
    if (strlen($message) > 500) {
        $message = substr($message, 0, 500);
    }

    // ── Query LightRAG directly with the user's text ──────────
    // LightRAG handles retrieval internally — no embeddings needed from PHP
    $ragResult = $lightrag ? $lightrag->query($message, 'naive', 3) : null;

    file_put_contents(__DIR__ . '/rag_debug.log',
    date('Y-m-d H:i:s') . "\n" .
    "LightRAG: " . ($lightrag ? "connected" : "NULL") . "\n" .
    "Query: $message\n" .
    "Raw result: " . json_encode($ragResult) . "\n\n",
    FILE_APPEND
);
    error_log("LightRAG object: " . ($lightrag ? "OK" : "NULL"));

    // LightRAG returns the answer directly in the response field
    $context = $ragResult['response'] ?? $ragResult['data'] ?? null;

    error_log("RAG raw result: " . json_encode($ragResult));

    if (!empty($context)) {
        // Use OpenAI to reformat/personalize the LightRAG response
        $response = $openai->chat()->create([
    'model' => 'llama-3.3-70b-versatile',  // Groq model
        #$response = $openai->chat()->create([
            #'model'    => 'gpt-3.5-turbo',
            'messages' => [
                [
                    'role'    => 'system',
                    'content' => 'You are a helpful assistant for SnacksOnline. Use the provided FAQ context to answer the user\'s question in a friendly, concise way. If the context does not answer the question, say so and suggest contacting support@snacksonline.com.'
                ],
                [
                    'role'    => 'user',
                    'content' => "FAQ Context:\n" . $context . "\n\nUser question: " . $message
                ],
            ],
            'max_tokens' => 300,
        ]);

        echo json_encode([
            'answer' => $response->choices[0]->message->content,
            'source' => 'RAG'
        ]);

    } else {
        echo json_encode([
            'answer' => "I couldn't find a match in our FAQs. Please visit the FAQ page or contact support@snacksonline.com.",
            'source' => 'fallback'
        ]);
    }

} catch (Exception $e) {
    file_put_contents(__DIR__ . '/chat_error.log',
        date('Y-m-d H:i:s') . " - " . $e->getMessage() . "\n",
        FILE_APPEND
    );
    echo json_encode(['answer' => 'Sorry, there was an error. Please try again.']);
}