<?php require_once 'views/header.php'; ?>

<div class="container">
    <h1>Тест по дисциплине</h1>
    
    <?php if (isset($error)): ?>
    <div class="alert alert-danger">
        <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['errors'])): ?>
        <div class="alert alert-danger">
            <?php 
            foreach ($_SESSION['errors'] as $error) {
                echo htmlspecialchars($error) . '<br>';
            }
            unset($_SESSION['errors']);
            ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_SESSION['success']) ?>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    
    <form action="index.php?controller=test&action=save" method="post">
        <div class="form-group">
            <label for="fio">ФИО:</label>
            <input type="text" class="form-control" id="fio" name="fio" required>
        </div>

        <div class="questions">
            <!-- Вопрос 1 -->
            <div class="form-group">
                <p>1. Что такое PHP?</p>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="answers[1]" value="a" required>
                    <label class="form-check-label">A) Язык программирования общего назначения</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="answers[1]" value="b">
                    <label class="form-check-label">B) Скриптовый язык программирования</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="answers[1]" value="c">
                    <label class="form-check-label">C) Система управления базами данных</label>
                </div>
            </div>

            <!-- Вопрос 2 -->
            <div class="form-group">
                <p>2. Какая функция используется для подключения к MySQL в PDO?</p>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="answers[2]" value="a" required>
                    <label class="form-check-label">A) mysql_connect()</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="answers[2]" value="b">
                    <label class="form-check-label">B) new PDO()</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="answers[2]" value="c">
                    <label class="form-check-label">C) mysqli_connect()</label>
                </div>
            </div>

            <!-- Вопрос 3 -->
            <div class="form-group">
                <p>3. Как получить значение POST-параметра в PHP?</p>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="answers[3]" value="a" required>
                    <label class="form-check-label">A) $POST['parameter']</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="answers[3]" value="b">
                    <label class="form-check-label">B) $_POST['parameter']</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="answers[3]" value="c">
                    <label class="form-check-label">C) get_post('parameter')</label>
                </div>
            </div>
        </div>

        <input type="hidden" name="is_correct" id="is_correct" value="0">
        <button type="submit" class="btn btn-primary" onclick="checkAnswers()">Отправить</button>
    </form>

    <script>
        function checkAnswers() {
            const correctAnswers = {
                1: 'b',
                2: 'b',
                3: 'b'
            };

            let isCorrect = true;
            const answers = document.querySelectorAll('input[type="radio"]:checked');
            
            answers.forEach(answer => {
                const questionNum = answer.name.match(/\d+/)[0];
                if (answer.value !== correctAnswers[questionNum]) {
                    isCorrect = false;
                }
            });

            document.getElementById('is_correct').value = isCorrect ? '1' : '0';
        }
    </script>
</div>

<?php require_once 'views/footer.php'; ?>