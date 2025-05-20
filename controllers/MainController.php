<?php
class MainController {
    public function index() {
        // Ваша логика для главной страницы
        $data = [
            'title' => 'Главная страница',
            'content' => 'Добро пожаловать на мой сайт!'
        ];
        
        // Подключаем представление
        require_once __DIR__ . '/../views/home.php';
    }
    
    public function handle() {
        // Можно добавить логику для главной страницы
        require_once __DIR__ . '/../views/home.php';
    }
}