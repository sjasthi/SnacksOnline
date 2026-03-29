<?php
// ── Database Configuration ────────────────────────────────────
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'snacksonline');
define('DB_CHARSET', 'utf8mb4');

// ── PDO Connection ────────────────────────────────────────────
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch (PDOException $e) {
    die('<div style="font-family:Arial;padding:40px;color:#c0392b;">
        <h2>Database Connection Error</h2>
        <p>Could not connect to MySQL. Please check your database settings in <code>includes/db.php</code>.</p>
        <p>Make sure XAMPP MySQL is running and the database <strong>snacksonline</strong> exists.</p>
        <small>' . htmlspecialchars($e->getMessage()) . '</small>
    </div>');
}

// ── LightRAG Connection ───────────────────────────────────────
require_once __DIR__ . '/lightrag.php';

try {
    $lightrag = new LightRAG([
        // FIX: LightRAG binds to 0.0.0.0:8080 — use 127.0.0.1 explicitly
        // If running on a remote server, replace with that server's IP
        'host'    => 'http://172.28.85.61:8080',
        'api_key' => '' // leave empty if LightRAG has no auth enabled
    ]);
} catch (Exception $e) {
    file_put_contents(__DIR__ . '/../chat_error.log',
        date('Y-m-d H:i:s') . " - LightRAG init failed: " . $e->getMessage() . "\n",
        FILE_APPEND
    );
    $lightrag = null;
}
