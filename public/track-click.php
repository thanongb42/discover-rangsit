<?php
// Bypass router — direct PHP file
require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/core/Model.php';
require_once __DIR__ . '/../app/models/DeliveryLink.php';
require_once __DIR__ . '/../config/config.php';

$placeId  = (int)($_GET['place'] ?? 0);
$platform = preg_replace('/[^a-z]/', '', strtolower($_GET['platform'] ?? ''));

if (!$placeId || !$platform) {
    http_response_code(400);
    exit('Bad Request');
}

$model = new DeliveryLink();
$links = $model->getByPlace($placeId);

$targetUrl = null;
foreach ($links as $link) {
    if ($link->platform === $platform) {
        $targetUrl = $link->url;
        break;
    }
}

if (!$targetUrl) {
    http_response_code(404);
    exit('Not found');
}

// Log click asynchronously-safe way (fire and forget)
$model->logClick(
    $placeId,
    $platform,
    hash('sha256', $_SERVER['REMOTE_ADDR'] ?? ''),
    $_SERVER['HTTP_USER_AGENT'] ?? '',
    $_SERVER['HTTP_REFERER']    ?? ''
);
$model->incrementClick($placeId, $platform);

// Redirect to real URL (only from DB — no open redirect)
header('Location: ' . $targetUrl, true, 302);
exit;
