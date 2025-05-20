<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход администратора</title>
    <link rel="stylesheet" href="public/css/styles.css">
</head>
<body>
    <h2>Вход администратора</h2>
    <?php if (!empty($error)): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="post" action="index.php?controller=authorization&action=login">
        <label for="login">Логин:</label><br>
        <input type="text" id="login" name="login" required><br><br>
        <label for="password">Пароль:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        <button type="submit">Войти</button>
    </form>
</body>
</html>
