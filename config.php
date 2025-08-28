<?php
// config.php - DB + session + helpers
define('DB_HOST', 'localhost');
define('DB_USER', 'root');    // <-- change as needed
define('DB_PASS', '');        // <-- change as needed
define('DB_NAME', 'nextintech');

mysqli_report(MYSQLI_REPORT_OFF);
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($mysqli->connect_errno) {
    // Friendly message for dev; in production log and show generic error
    http_response_code(500);
    die('Database connection failed. Please check config.php settings.');
}

// sessions
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// HTML escape helper
function h($v) {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

// CSRF helpers
if (empty($_SESSION['csrf_token'])) {
    try {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    } catch (Exception $e) {
        $_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(32));
    }
}
function csrf_field() {
    $t = $_SESSION['csrf_token'] ?? '';
    echo '<input type="hidden" name="csrf" value="'.h($t).'">';
}
function verify_csrf($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], (string)$token);
}

// require auth guard
function require_auth() {
    if (empty($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
}
