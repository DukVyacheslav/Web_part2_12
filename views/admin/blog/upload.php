<?php require_once 'views/header.php'; ?>

<div class="container">
    <h1>Загрузка сообщений блога</h1>
    
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
            <?php 
            echo htmlspecialchars($_SESSION['success']);
            unset($_SESSION['success']);
            ?>
        </div>
    <?php endif; ?>
    
    <!-- Форма создания нового сообщения блога -->
    <form action="index.php?controller=blog&action=save" method="post" enctype="multipart/form-data" class="mb-4">
        <div class="form-group">
            <label for="name">Тема сообщения:</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        
        <div class="form-group">
            <label for="img">Изображение:</label>
            <input type="file" class="form-control-file" id="img" name="img">
        </div>
        
        <div class="form-group">
            <label for="text">Текст сообщения:</label>
            <textarea class="form-control" id="text" name="text" rows="5" required></textarea>
        </div>
        
        <button type="submit" class="btn btn-primary">Сохранить</button>
    </form>

    <!-- Форма загрузки CSV файла -->
    <form action="index.php?controller=blog&action=upload" method="post" enctype="multipart/form-data" class="mb-4">
        <div class="form-group">
            <label for="csv_file">Выберите CSV файл:</label>
            <input type="file" class="form-control-file" id="csv_file" name="csv_file" accept=".csv" required>
            <small class="form-text text-muted">
                Файл должен содержать записи в формате: "тема","сообщение","изображение"<br>
                Максимальный размер файла: 5MB
            </small>
        </div>
        
        <button type="submit" class="btn btn-primary">Загрузить</button>
    </form>
</div>

<?php require_once 'views/footer.php'; ?>
