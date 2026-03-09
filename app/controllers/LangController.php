<?php
class LangController extends Controller {

    public function switchLang($code) {
        $allowed = ['th', 'en'];
        if (in_array($code, $allowed)) {
            $_SESSION['lang'] = $code;
        }
        // Redirect back to referring page, fall back to home
        $redirect = $_SERVER['HTTP_REFERER'] ?? BASE_URL . '/';
        header('Location: ' . $redirect);
        exit;
    }
}
