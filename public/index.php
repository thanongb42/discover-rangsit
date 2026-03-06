<?php

require_once '../config/config.php';
require_once '../app/core/Database.php';
require_once '../app/core/Model.php';
require_once '../app/core/Controller.php';
require_once '../app/core/Router.php';

session_start();

$router = new Router();
require_once '../routes.php';

$router->dispatch($_SERVER['REQUEST_URI']);
