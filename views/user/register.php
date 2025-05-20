<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация пользователя</title>
    <link rel="stylesheet" href="public/css/styles.css">
</head>
<body>
    <h2>Регистрация пользователя</h2>
    <?php if (!empty($errors)): ?>
        <div style="color: red;">
            <?php foreach ($errors as $error): ?>
                <p><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <form method="post" action="index.php?controller=user&action=register" id="registerForm">
        <label for="fio">ФИО:</label><br>
        <input type="text" id="fio" name="fio" required><br><br>
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>
        <label for="login">Логин:</label><br>
        <input type="text" id="login" name="login" required>
        <button type="button" id="checkLoginBtn">Проверить занятость</button>
        <span id="loginCheckResult" style="margin-left:10px;"></span>
        <br><br>
        <label for="password">Пароль:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        <button type="submit">Зарегистрироваться</button>
    </form>
    <script>
        document.getElementById('checkLoginBtn').addEventListener('click', function() {
            var login = document.getElementById('login').value;
            var resultSpan = document.getElementById('loginCheckResult');
            if (!login) {
                resultSpan.textContent = 'Введите логин для проверки';
                resultSpan.style.color = 'red';
                return;
            }
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'index.php?controller=user&action=checkLoginAvailability', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.exists) {
                            resultSpan.textContent = 'Логин занят';
                            resultSpan.style.color = 'red';
                        } else {
                            resultSpan.textContent = 'Логин свободен';
                            resultSpan.style.color = 'green';
                        }
                    } else {
                        resultSpan.textContent = 'Ошибка проверки';
                        resultSpan.style.color = 'red';
                    }
                }
            };
            xhr.send('login=' + encodeURIComponent(login));
        });
    </script>
    <p>Уже зарегистрированы? <a href="index.php?controller=user&action=login">Войти</a></p>
</body>
</html>
