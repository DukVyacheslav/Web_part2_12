<?php
class View {
    public function render($template, $title, $data = []) {
        // Извлекаем переменные из массива данных
        extract($data);
        
        // Подключаем шапку сайта
        require_once __DIR__ . '/header.php';
        
        // Подключаем основной шаблон
        require_once __DIR__ . '/' . $template;
        
        // Подключаем подвал сайта
        require_once __DIR__ . '/footer.php';
    }
}
?>