<?php
function e(?string $value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}
function clean(?string $value): string {
    return trim((string)$value);
}
function redirect(string $url): never {
    header("Location: {$url}");
    exit;
}
function flash(string $type, string $message): void {
    $_SESSION["flash"] = ["type"=>$type, "message"=>$message];
}
function getFlash(): ?array {
    $f = $_SESSION["flash"] ?? null;
    unset($_SESSION["flash"]);
    return $f;
}
function csrfToken(): string {
    if (empty($_SESSION["csrf_token"])) $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
    return $_SESSION["csrf_token"];
}
function verifyCsrf(?string $token): bool {
    return is_string($token) && isset($_SESSION["csrf_token"]) && hash_equals($_SESSION["csrf_token"], $token);
}
function rupiah(float|int|string $value): string {
    return "Rp " . number_format((float)$value, 0, ",", ".");
}
