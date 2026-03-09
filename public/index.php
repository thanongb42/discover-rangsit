<?php

require_once '../config/config.php';
require_once '../app/core/Database.php';
require_once '../app/core/Model.php';
require_once '../app/core/Controller.php';
require_once '../app/core/Router.php';

session_start();

// Load language helper
require_once '../app/helpers/lang_helper.php';

$router = new Router();
require_once '../routes.php';

// Site Visit Tracking (Safe Mode)
try {
    $analyticsFile = APP_ROOT . '/app/models/Analytics.php';
    if (file_exists($analyticsFile)) {
        require_once $analyticsFile;
        $analytics = new Analytics();
        $analytics->logVisit([
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'page_url' => $_SERVER['REQUEST_URI'],
            'user_id' => $_SESSION['user_id'] ?? NULL
        ]);
    }
} catch (Exception $e) {
    // Keep site running even if tracking fails
}

$router->dispatch($_SERVER['REQUEST_URI']);
