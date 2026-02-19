<?php
class Middleware {
    public static function auth() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if(!isset($_SESSION['user_id'])) {
            header('Location: '.SITE_URL.'/login');
            exit;
        }
    }

    public static function role($roles=[]) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if(!is_array($roles)){
            $roles = [$roles];
        }

        if(!isset($_SESSION['role']) || !in_array($_SESSION['role'],$roles)) {
            http_response_code(403);
            echo "Access Denied";
            exit;
        }
    }
}
