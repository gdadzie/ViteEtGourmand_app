<?php
declare(strict_types=1);

namespace Core;

final class Security
{
    private const CSRF_KEY = 'csrf_token';

    public static function startSession(): void
    {
        if (session_status() !== PHP_SESSION_NONE) return;
        session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax', 'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')]);
        session_start();
    }

    public static function token(): string
    {
        if (empty($_SESSION[self::CSRF_KEY])) $_SESSION[self::CSRF_KEY] = bin2hex(random_bytes(32));
        return $_SESSION[self::CSRF_KEY];
    }

    public static function verifyPost(): void
    {
        $token = $_POST['_csrf'] ?? '';
        if (!is_string($token) || !hash_equals(self::token(), $token)) {
            http_response_code(419);
            exit('Votre session a expiré. Actualisez la page puis réessayez.');
        }
    }

    public static function injectCsrfFields(string $html): string
    {
        $field = '<input type="hidden" name="_csrf" value="' . htmlspecialchars(self::token(), ENT_QUOTES, 'UTF-8') . '">';
        $html = preg_replace('/<form\\b(?=[^>]*\\bmethod\\s*=\\s*["\']?post\\b)[^>]*>/i', '$0' . $field, $html) ?? $html;
        $html = str_ireplace('</head>', '<link rel="stylesheet" href="assets/css/responsive.css"></head>', $html);
        return str_ireplace('</body>', '<script src="assets/js/responsive-tables.js"></script></body>', $html);
    }
}
