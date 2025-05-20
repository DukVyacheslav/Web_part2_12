<?php require_once 'views/header.php'; ?>

<div class="container">
    <h1>Результаты тестов</h1>
    
    <table class="table">
        <thead>
            <tr>
                <th>Дата</th>
                <th>ФИО</th>
                <th>Ответы</th>
                <th>Результат</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($results as $result): ?>
            <tr>
                <td><?= htmlspecialchars($result->date) ?></td>
                <td><?= htmlspecialchars($result->fio) ?></td>
                <td><?= htmlspecialchars($result->answers) ?></td>
                <td><?= $result->is_correct ? 'Верно' : 'Неверно' ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once 'views/footer.php'; ?>