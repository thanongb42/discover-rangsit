<?php

require_once '../config/config.php';
require_once '../app/core/Database.php';
require_once '../app/core/Model.php';
require_once '../app/core/Controller.php';
require_once '../app/core/Router.php';

session_start();

$router = new Router();
require_once '../routes.php';

// Site Visit Tracking
require_once '../app/models/Analytics.php';
$analytics = new Analytics();
$analytics->logVisit([
    'ip_address' => $_SERVER['REMOTE_ADDR'],
    'user_agent' => $_SERVER['HTTP_USER_AGENT'],
    'page_url' => $_SERVER['REQUEST_URI'],
    'user_id' => $_SESSION['user_id'] ?? NULL
]);

$router->dispatch($_SERVER['REQUEST_URI']);
