<?php
class Controller {
    public function model($model) {
        require_once '../app/models/' . $model . '.php';
        return new $model();
    }

    public function view($view, $data = []) {
        if (file_exists('../app/views/' . $view . '.php')) {
            require_once '../app/views/' . $view . '.php';
        } else {
            die("View does not exist.");
        }
    }

    public function logActivity($action, $description = '') {
        try {
            $logFile = APP_ROOT . '/app/models/ActivityLog.php';
            if (file_exists($logFile)) {
                require_once $logFile;
                $logModel = new ActivityLog();
                $logModel->log([
                    'user_id' => $_SESSION['user_id'] ?? null,
                    'action' => $action,
                    'description' => $description
                ]);
            }
        } catch (Exception $e) {
            // Log failed, but continue
        }
    }
}