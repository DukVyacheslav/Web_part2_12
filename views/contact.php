<?php require_once 'views/header.php'; ?>

<div class="container">
    <h1>Контакт</h1>
    
    <?php if (isset($success_message)): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($success_message) ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error_message) ?>
        </div>
    <?php endif; ?>
    
    <div class="card">
        <div class="card-body">
            <h2 class="card-title">Связаться со мной</h2>
            <form method="post" action="index.php?controller=contact&action=index">
                <div class="form-group">
                    <label for="name">Имя:</label>
                    <input type="text" class="form-control" id="name" name="name" 
                           value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" 
                           value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="phone">Телефон:</label>
                    <input type="tel" class="form-control" id="phone" name="phone" 
                           value="<?= isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '' ?>" 
                           placeholder="+7 (999) 999-99-99" required>
                </div>
                
                <div class="form-group">
                    <label for="birthdate">Дата рождения:</label>
                    <input type="date" class="form-control" id="birthdate" name="birthdate" 
                           value="<?= isset($_POST['birthdate']) ? htmlspecialchars($_POST['birthdate']) : '' ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="age">Возраст:</label>
                    <input type="number" class="form-control" id="age" name="age" min="0" max="150" 
                           value="<?= isset($_POST['age']) ? htmlspecialchars($_POST['age']) : '' ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="gender">Пол:</label>
                    <select class="form-control" id="gender" name="gender" required>
                        <option value="">Выберите пол</option>
                        <option value="Мужской" <?= (isset($_POST['gender']) && $_POST['gender'] === 'Мужской') ? 'selected' : '' ?>>Мужской</option>
                        <option value="Женский" <?= (isset($_POST['gender']) && $_POST['gender'] === 'Женский') ? 'selected' : '' ?>>Женский</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="message">Сообщение:</label>
                    <textarea class="form-control" id="message" name="message" rows="5"><?= isset($_POST['message']) ? htmlspecialchars($_POST['message']) : '' ?></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary">Отправить</button>
            </form>
        </div>
    </div>
</div>

<?php require_once 'views/footer.php'; ?>