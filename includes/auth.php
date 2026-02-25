<?php
// ── Session Start ─────────────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ── Auth Helper Functions ─────────────────────────────────────

function is_logged_in(): bool {
    return isset($_SESSION['user_id']);
}

function is_admin(): bool {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function is_customer(): bool {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'customer';
}

function current_user_id(): ?int {
    return $_SESSION['user_id'] ?? null;
}

function current_user_name(): string {
    return $_SESSION['name'] ?? 'Guest';
}

function current_user_email(): string {
    return $_SESSION['email'] ?? '';
}

// ── Redirect Helpers ──────────────────────────────────────────

function redirect(string $url): void {
    header("Location: $url");
    exit;
}

function require_login(): void {
    if (!is_logged_in()) {
        redirect('/snacksonline/login.php?next=' . urlencode($_SERVER['REQUEST_URI']));
    }
}

function require_admin(): void {
    if (!is_admin()) {
        redirect('/snacksonline/index.php');
    }
}

function require_customer(): void {
    if (!is_logged_in()) {
        redirect('/snacksonline/login.php?next=' . urlencode($_SERVER['REQUEST_URI']));
    }
}

// ── Flash Messages ────────────────────────────────────────────

function set_flash(string $message, string $type = 'success'): void {
    $_SESSION['flash'] = ['message' => $message, 'type' => $type];
}

function get_flash(): ?array {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

// ── Cart Helper ───────────────────────────────────────────────

function get_cart(): array {
    return $_SESSION['cart'] ?? [];
}

function cart_count(): int {
    return array_sum($_SESSION['cart'] ?? []);
}
