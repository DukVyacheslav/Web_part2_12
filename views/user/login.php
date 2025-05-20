<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход пользователя</title>
    <link rel="stylesheet" href="public/css/styles.css">
</head>
<body>
    <h2>Вход пользователя</h2>
    <?php if (!empty($errors)): ?>
        <div style="color: red;">
            <?php foreach ($errors as $error): ?>
                <p><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <form method="post" action="index.php?controller=user&action=login">
        <label for="login">Логин:</label><br>
        <input type="text" id="login" name="login" required><br><br>
        <label for="password">Пароль:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        <button type="submit">Войти</button>
    </form>
    <p>Нет аккаунта? <a href="index.php?controller=user&action=register">Зарегистрироваться</a></p>
</body>
</html>
