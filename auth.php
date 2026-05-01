<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function csrf_token(): string
{
    if (empty($_SESSION["csrf_token"])) {
        $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
    }
    return $_SESSION["csrf_token"];
}

function verify_csrf_or_fail(): void
{
    $token = $_POST["csrf_token"] ?? "";
    $sessionToken = $_SESSION["csrf_token"] ?? "";
    if (!$token || !$sessionToken || !hash_equals($sessionToken, $token)) {
        http_response_code(419);
        exit("Session expiree. Rechargez la page et recommencez.");
    }
}

function is_logged_in(): bool
{
    return isset($_SESSION["user_id"]);
}

function require_login(): void
{
    if (!is_logged_in()) {
        header("Location: veloura.php?error=login_required");
        exit;
    }
}

function require_admin(): void
{
    require_login();
    if (($_SESSION["role"] ?? "client") !== "admin") {
        header("Location: accueil.php");
        exit;
    }
}
?>
