<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__.'/../models/User.php';
require_once __DIR__.'/../views/View.php';

class UserController {
    private $model;
    private $view;

    public function __construct() {
        $this->model = new User();
        $this->view = new View();
    }

    public function register() {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->model->validate($_POST)) {
                $this->model->fio = $_POST['fio'];
                $this->model->email = $_POST['email'];
                $this->model->login = $_POST['login'];
                $this->model->password = md5($_POST['password']);

                if ($this->model->isLoginExists($this->model->login)) {
                    $errors[] = 'Пользователь с таким логином уже существует';
                } else {
                    if ($this->model->save()) {
                        $_SESSION['user'] = [
                            'fio' => $this->model->fio,
                            'login' => $this->model->login
                        ];
                        header('Location: index.php');
                        exit;
                    } else {
                        $errors[] = 'Ошибка при сохранении пользователя';
                    }
                }
            } else {
                $errors[] = 'Пожалуйста, заполните все поля корректно';
            }
        }
        $this->view->render('user/register.php', 'Регистрация пользователя', ['errors' => $errors]);
    }

    public function login() {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $login = $_POST['login'] ?? '';
            $password = md5($_POST['password'] ?? '');

            $user = $this->model->findByLoginAndPassword($login, $password);
            if ($user) {
                $_SESSION['user'] = [
                    'fio' => $user->fio,
                    'login' => $user->login
                ];
                header('Location: index.php');
                exit;
            } else {
                $errors[] = 'Неверный логин или пароль';
            }
        }
        $this->view->render('user/login.php', 'Вход пользователя', ['errors' => $errors]);
    }

    public function logout() {
        unset($_SESSION['user']);
        session_destroy();
        header('Location: index.php');
        exit;
    }

    public function checkLoginAvailability() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $login = $_POST['login'] ?? '';
            $exists = $this->model->isLoginExists($login);
            header('Content-Type: application/json');
            echo json_encode(['exists' => $exists]);
            exit;
        }
    }
}
?>
