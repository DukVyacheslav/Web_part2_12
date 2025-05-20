<?php
// Простой тестовый скрипт для проверки загрузки и удаления записей блога
// Требуется запускать в среде с поддержкой сессий и доступом к базе данных

session_start();

// Генерация CSRF токена для теста
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function testBlogPostCreation() {
    // Имитация POST запроса на сохранение записи
    $_SERVER['REQUEST_METHOD'] = 'POST';
    $_POST = [
        'name' => 'Test Title',
        'text' => 'Test Content',
        'csrf_token' => $_SESSION['csrf_token']
    ];
    $_FILES = [];

    ob_start();
    include 'index.php'; // Предполагается, что index.php маршрутизирует запросы
    ob_end_clean();

    // Проверка наличия записи в базе данных
    $db = new PDO('sqlite:database/blog.db'); // Путь к БД может отличаться
    $stmt = $db->prepare("SELECT * FROM blog WHERE name = :name");
    $stmt->execute([':name' => 'Test Title']);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($post) {
        echo "TestBlogPostCreation passed\n";
    } else {
        echo "TestBlogPostCreation failed\n";
    }
}

function testBlogPostDeletion() {
    // Получаем ID тестовой записи
    $db = new PDO('sqlite:database/blog.db');
    $stmt = $db->prepare("SELECT * FROM blog WHERE name = :name");
    $stmt->execute([':name' => 'Test Title']);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$post) {
        echo "TestBlogPostDeletion failed: test post not found\n";
        return;
    }

    // Имитация POST запроса на удаление записи
    $_SERVER['REQUEST_METHOD'] = 'POST';
    $_POST = [
        'id' => $post['id'],
        'csrf_token' => $_SESSION['csrf_token']
    ];

    ob_start();
    include 'index.php';
    ob_end_clean();

    // Проверка удаления записи
    $stmt = $db->prepare("SELECT * FROM blog WHERE id = :id");
    $stmt->execute([':id' => $post['id']]);
    $postDeleted = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$postDeleted) {
        echo "TestBlogPostDeletion passed\n";
    } else {
        echo "TestBlogPostDeletion failed\n";
    }
}

// Запуск тестов
testBlogPostCreation();
testBlogPostDeletion();
?>
