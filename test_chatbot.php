<?php
/**
 * test_chatbot.php — Accuracy test for SnacksOnline FAQ chatbot
 * Run from command line: php test_chatbot.php
 */
require_once __DIR__ . '/includes/db.php';

$testQuestions = [
    // Ordering
    ['q' => 'Can I cancel an order after placing it?',        'expect' => '2 hours'],
    ['q' => 'Can I change my delivery address after checkout?', 'expect' => 'dispatched'],
    ['q' => 'Can I order items in bulk?',                     'expect' => '50 units'],
    ['q' => 'Do you offer order confirmations?',              'expect' => 'confirmation email'],
    ['q' => 'Can I reorder past purchases easily?',           'expect' => 'order history'],

    // Customer Service
    ['q' => 'What are your support hours?',                   'expect' => '9 AM'],
    ['q' => 'Do you offer live chat support?',                'expect' => '24/7'],
    ['q' => 'How quickly does support respond?',              'expect' => '24 hours'],
    ['q' => 'Can I contact support by phone?',                'expect' => 'hotline'],
    ['q' => 'Do you offer multilingual support?',             'expect' => 'Spanish'],

    // Payments
    ['q' => 'Is my payment information secure?',              'expect' => 'encryption'],
    ['q' => 'Do you offer discounts for bulk orders?',        'expect' => 'discounts'],
    ['q' => 'Can I use gift cards?',                          'expect' => 'gift cards'],
    ['q' => 'Do you charge extra fees?',                      'expect' => 'hidden fees'],
    ['q' => 'Can I split payments across methods?',           'expect' => 'one payment method'],

    // Returns
    ['q' => 'What if my item arrives damaged?',               'expect' => '48 hours'],
    ['q' => 'How do I get a refund?',                         'expect' => '5'],
    ['q' => 'Do you cover return shipping costs?',            'expect' => 'shipping costs'],
    ['q' => 'Can I exchange an item instead of returning it?', 'expect' => 'exchange'],
    ['q' => 'Are there items that cannot be returned?',       'expect' => 'opened food'],

    // Shipping
    ['q' => 'Can I schedule a delivery time?',                'expect' => 'delivery windows'],
    ['q' => 'Do you offer same-day delivery?',                'expect' => 'noon'],
    ['q' => 'Can I track my delivery in real time?',          'expect' => 'tracking link'],
    ['q' => 'Do you deliver to offices or workplaces?',       'expect' => 'commercial'],
    ['q' => 'What happens if I miss a delivery?',             'expect' => 'next business day'],

    // Technical Assistance
    ['q' => 'How do I update my account details?',            'expect' => 'profile settings'],
    ['q' => 'Why am I not receiving confirmation emails?',    'expect' => 'spam'],
    ['q' => 'Can I reset my password if I forget it?',        'expect' => 'Forgot Password'],
    ['q' => 'How do I delete my account?',                    'expect' => 'account deletion'],
    ['q' => 'Can I use the site on mobile devices?',          'expect' => 'mobile-friendly'],
];

$pass = 0; $fail = 0; $noContext = 0;
$results = [];

echo "🧪 Running FAQ accuracy tests...\n";
echo str_repeat('-', 70) . "\n\n";

foreach ($testQuestions as $test) {
    $ragResult = $lightrag->query($test['q'], 'naive', 5);
    $context   = $ragResult['response'] ?? '';

    if (empty($context) || strpos($context, 'no-context') !== false || strpos($context, 'No relevant') !== false) {
        echo "⚠ NO CTX | Q: {$test['q']}\n";
        $noContext++;
        $fail++;
        $results[] = ['status' => 'no_context', 'q' => $test['q']];
        continue;
    }

    $hit = stripos($context, $test['expect']) !== false;

    if ($hit) {
        echo "✓ PASS   | Q: {$test['q']}\n";
        $pass++;
        $results[] = ['status' => 'pass', 'q' => $test['q']];
    } else {
        echo "✗ FAIL   | Q: {$test['q']}\n";
        echo "           Expected keyword: '{$test['expect']}'\n";
        echo "           Got: " . substr($context, 0, 120) . "...\n";
        $fail++;
        $results[] = ['status' => 'fail', 'q' => $test['q'], 'expected' => $test['expect'], 'got' => substr($context, 0, 120)];
    }

    usleep(300000); // avoid rate limiting during test
}

$total = $pass + $fail;
$pct   = round($pass / $total * 100);

echo "\n" . str_repeat('-', 70) . "\n";
echo "📊 Results:\n";
echo "   ✓ Passed:         $pass / $total ($pct%)\n";
echo "   ✗ Failed:         $fail / $total\n";
echo "   ⚠ No context:     $noContext / $total\n\n";

// Category breakdown
$categories = ['Ordering', 'Customer Service', 'Payments', 'Returns', 'Shipping', 'Technical Assistance'];
$categoryMap = [
    'Ordering'             => array_slice($results, 0, 5),
    'Customer Service'     => array_slice($results, 5, 5),
    'Payments'             => array_slice($results, 10, 5),
    'Returns'              => array_slice($results, 15, 5),
    'Shipping'             => array_slice($results, 20, 5),
    'Technical Assistance' => array_slice($results, 25, 5),
];

echo "📂 By category:\n";
foreach ($categoryMap as $cat => $catResults) {
    $catPass = count(array_filter($catResults, fn($r) => $r['status'] === 'pass'));
    $catTotal = count($catResults);
    $bar = str_repeat('█', $catPass) . str_repeat('░', $catTotal - $catPass);
    echo "   $bar $catPass/$catTotal — $cat\n";
}

echo "\n";
if ($pct >= 80) {
    echo "🎉 Good accuracy! Consider tweaking the system prompt for remaining failures.\n";
} elseif ($pct >= 60) {
    echo "⚠ Moderate accuracy. Try reducing CHUNK_SIZE in .env and re-embedding.\n";
} else {
    echo "❌ Low accuracy. Check that text_chunks = 43 in LightRAG before re-testing.\n";
}