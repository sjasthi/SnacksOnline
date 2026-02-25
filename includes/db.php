<?php
// ── Database Configuration ────────────────────────────────────
// Change these values to match your XAMPP / cPanel MySQL settings
define('DB_HOST', 'localhost');
define('DB_USER', 'root');        // XAMPP default. Change for cPanel.
define('DB_PASS', '');            // XAMPP default is empty. Change for cPanel.
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
    // Show a friendly error — never expose PDO details in production
    die('<div style="font-family:Arial;padding:40px;color:#c0392b;">
        <h2>Database Connection Error</h2>
        <p>Could not connect to MySQL. Please check your database settings in <code>includes/db.php</code>.</p>
        <p>Make sure XAMPP MySQL is running and the database <strong>snacksonline</strong> exists.</p>
        <small>' . htmlspecialchars($e->getMessage()) . '</small>
    </div>');
}
