<?php
session_start();

class AuthorizationController {
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $login = $_POST['login'] ?? '';
            $password = $_POST['password'] ?? '';

            // Проверка логина и md5-хеша пароля
            if ($login === 'admin@gmail.com' && md5($password) === 'd8578edf8458ce06fbc5bb76a58c5ca4') {
                $_SESSION['isAdmin'] = 1;
                header('Location: index.php?controller=admin_blog&action=editor');
                exit;
            } else {
                $error = 'Неверный логин или пароль';
            }
        }

        require_once __DIR__ . '/../views/authorization/login.php';
    }

    public function logout() {
        session_start();
        unset($_SESSION['isAdmin']);
        session_destroy();
        header('Location: index.php?controller=authorization&action=login');
        exit;
    }
}
?>
