<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title) ?></title>
    <link rel="stylesheet" href="public/css/styles.css">
</head>
<body>
    <h1>Загрузка сообщений гостевой книги</h1>
    <?php if (!empty($_SESSION['errors'])): ?>
        <div style="color: red;">
            <?php foreach ($_SESSION['errors'] as $error): ?>
                <p><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        </div>
        <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>
    <?php if (!empty($_SESSION['success'])): ?>
        <div style="color: green;">
            <p><?= htmlspecialchars($_SESSION['success']) ?></p>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data" action="index.php?controller=admin_guestbook&action=upload">
        <label for="messages">Выберите CSV файл с сообщениями:</label><br>
        <input type="file" id="messages" name="messages" accept=".csv" required><br><br>
        <button type="submit">Загрузить</button>
    </form>
    <a href="index.php?controller=authorization&action=logout">Выйти</a>
</body>
</html>
