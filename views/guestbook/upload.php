<?php require_once 'views/header.php'; ?>

<div class="container">
    <h1>Загрузка сообщений гостевой книги</h1>
    
    <form action="index.php?controller=guestbook&action=upload" method="post" enctype="multipart/form-data" class="mb-4">
        <div class="form-group">
            <label for="messages">Выберите файл с сообщениями:</label>
            <input type="file" class="form-control-file" id="messages" name="messages" required>
            <small class="form-text text-muted">Файл должен содержать сообщения в формате: Дата;ФИО;Email;Сообщение</small>
        </div>
        
        <button type="submit" class="btn btn-primary">Загрузить</button>
    </form>
</div>

<?php require_once 'views/footer.php'; ?>