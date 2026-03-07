<?php
/**
 * Language helper — call t('key') anywhere in views/controllers.
 * Language is stored in $_SESSION['lang'] (default: 'th').
 */

function t(string $key, string $fallback = ''): string {
    static $translations = null;
    static $loadedLang   = null;

    $lang = $_SESSION['lang'] ?? 'th';

    // Reload if language changed or first call
    if ($translations === null || $loadedLang !== $lang) {
        $file = APP_ROOT . "/config/lang/{$lang}.php";
        $translations = file_exists($file) ? require $file : [];
        $loadedLang   = $lang;
    }

    return $translations[$key] ?? ($fallback !== '' ? $fallback : $key);
}

function currentLang(): string {
    return $_SESSION['lang'] ?? 'th';
}

function isLang(string $lang): bool {
    return currentLang() === $lang;
}
