<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'Мой сайт' ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <!-- Сначала загружаем Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Затем наши пользовательские стили -->
    <link rel="stylesheet" href="public/css/styles.css">
    <style>
        .nav-link {
            white-space: nowrap;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">
                            <i class="fas fa-home"></i> Главная
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?controller=blog">
                            <i class="fas fa-blog"></i> Блог
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?controller=test">
                            <i class="fas fa-tasks"></i> Тестирование
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?controller=guestbook">
                            <i class="fas fa-book"></i> Гостевая книга
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="index.php?controller=interests">
                            <i class="fas fa-star"></i> Интересы
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?controller=photo">
                            <i class="fas fa-camera"></i> Фотоальбом
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?controller=contact">
                            <i class="fas fa-envelope"></i> Контакты
                        </a>
                    </li>
                    <?php
                    if (session_status() == PHP_SESSION_NONE) {
                        session_start();
                    }
                    if (isset($_SESSION['user'])):
                    ?>
                    <li class="nav-item">
                        <span class="nav-link">Пользователь: <?= htmlspecialchars($_SESSION['user']['fio']) ?></span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?controller=user&action=logout">Выйти</a>
                    </li>
                    <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?controller=user&action=login">Войти</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?controller=user&action=register">Регистрация</a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</body>
</html>
