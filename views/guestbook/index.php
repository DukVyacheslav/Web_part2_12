<?php require_once 'views/header.php'; ?>

<div class="container">
    <div class="guestbook-container">
        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h1 class="card-title text-center mb-4">Гостевая книга</h1>
                        
                        <?php if (isset($_SESSION['success'])): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?= htmlspecialchars($_SESSION['success']) ?>
                                <button type="button" class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                            </div>
                            <?php unset($_SESSION['success']); ?>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?= htmlspecialchars($_SESSION['error']) ?>
                                <button type="button" class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                            </div>
                            <?php unset($_SESSION['error']); ?>
                        <?php endif; ?>

                        <form id="guestbookForm" action="index.php?controller=guestbook&action=save" method="post" class="needs-validation mb-4" novalidate>
                            <div class="form-group">
                                <label for="fio">ФИО:</label>
                                <input type="text" class="form-control" id="fio" name="fio" required 
                                       pattern="[А-Яа-яЁё\s-]+" 
                                       title="Пожалуйста, используйте только русские буквы, пробелы и дефис">
                                <div class="invalid-feedback">
                                    Пожалуйста, введите ваше ФИО (только русские буквы)
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">E-mail:</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                                <div class="invalid-feedback">
                                    Пожалуйста, введите корректный email адрес
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="message">Текст отзыва:</label>
                                <textarea class="form-control" id="message" name="message" rows="4" required 
                                          minlength="10" maxlength="500"></textarea>
                                <div class="invalid-feedback">
                                    Сообщение должно содержать от 10 до 500 символов
                                </div>
                                <small class="form-text text-muted">
                                    <span id="messageLength">0</span>/500 символов
                                </small>
                            </div>
                            
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary px-5">
                                    <i class="fas fa-paper-plane mr-2"></i>Отправить
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4">Сообщения</h2>
                        
                        <?php if (empty($messages)): ?>
                            <div id="guestbookMessages" class="alert alert-info">
                                Пока нет сообщений в гостевой книге
                            </div>
                        <?php else: ?>
                            <div id="guestbookMessages" class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Дата</th>
                                            <th>ФИО</th>
                                            <th>Email</th>
                                            <th>Сообщение</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($messages as $message): ?>
                                        <tr>
                                            <td><?= htmlspecialchars(date('d.m.Y H:i', strtotime($message->date))) ?></td>
                                            <td><?= htmlspecialchars($message->fio) ?></td>
                                            <td><a href="mailto:<?= htmlspecialchars($message->email) ?>"><?= htmlspecialchars($message->email) ?></a></td>
                                            <td><?= nl2br(htmlspecialchars($message->message)) ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Валидация формы
    const form = document.getElementById('guestbookForm');
    const messageTextarea = document.getElementById('message');
    const messageLength = document.getElementById('messageLength');

    // Обновление счетчика символов
    function updateCharCount() {
        const length = messageTextarea.value.length;
        messageLength.textContent = length;
        messageLength.style.color = length > 500 ? 'red' : 'inherit';
    }

    messageTextarea.addEventListener('input', updateCharCount);

    // Валидация формы перед отправкой
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    });

    // Автоматическое сохранение в localStorage
    const formInputs = form.querySelectorAll('input, textarea');
    
    // Восстановление данных из localStorage
    formInputs.forEach(input => {
        const savedValue = localStorage.getItem('guestbook_' + input.name);
        if (savedValue) {
            input.value = savedValue;
            updateCharCount();
        }

        // Сохранение при вводе
        input.addEventListener('input', function() {
            localStorage.setItem('guestbook_' + input.name, input.value);
        });
    });

    // Очистка localStorage после успешной отправки
    if (document.querySelector('.alert-success')) {
        formInputs.forEach(input => {
            localStorage.removeItem('guestbook_' + input.name);
        });
    }
});
</script>

<?php require_once 'views/footer.php'; ?>