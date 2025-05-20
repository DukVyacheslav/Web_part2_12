<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title) ?></title>
    <link rel="stylesheet" href="public/css/styles.css">
</head>
<body>
    <h1>Редактор блога</h1>
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
    <ul>
        <?php foreach ($posts as $post): ?>
            <li><?= htmlspecialchars($post['name']) ?> - <?= htmlspecialchars($post['data']) ?></li>
        <?php endforeach; ?>
    </ul>
    <a href="index.php?controller=authorization&action=logout">Выйти</a>
</body>
</html>
