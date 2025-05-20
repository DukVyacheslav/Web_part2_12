<?php require_once 'views/header.php'; ?>

<div class="container">
    <h1>Редактор блога</h1>
    
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
    
    <h2>Существующие записи:</h2>
    <?php foreach ($posts as $post): ?>
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($post->name) ?></h5>
            <h6 class="card-subtitle mb-2 text-muted"><?= htmlspecialchars($post->data) ?></h6>
            <?php if ($post->img): ?>
            <img src="public/images/blog/<?= htmlspecialchars($post->img) ?>" class="img-thumbnail mb-2" style="max-width: 200px" alt="<?= htmlspecialchars($post->name) ?>">
            <?php endif; ?>
            <p class="card-text"><?= nl2br(htmlspecialchars($post->text)) ?></p>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php require_once 'views/footer.php'; ?>