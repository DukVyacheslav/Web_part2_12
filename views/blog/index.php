<?php require_once 'views/header.php'; ?>

<div class="container">
    <h1>Мой блог</h1>

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

    <?php if (isset($error)): ?>
    <div class="alert alert-danger">
        <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>
    
    <?php if (empty($posts)): ?>
    <div class="alert alert-info">
        Записей в блоге пока нет.
    </div>
    <?php else: ?>
        <?php foreach ($posts as $post): ?>
        <div class="card mb-4" data-post-id="<?= $post->id ?>">
            <div class="card-header">
                <h2 class="card-title"><?= htmlspecialchars($post->name) ?></h2>
                <small class="text-muted"><?= htmlspecialchars($post->data) ?></small>
            </div>
            
            <?php if ($post->img): ?>
            <img src="public/images/blog/<?= htmlspecialchars($post->img) ?>" 
                 class="card-img-top" 
                 alt="<?= htmlspecialchars($post->name) ?>"
                 style="max-height: 400px; object-fit: contain;">
            <?php endif; ?>
            
            <div class="card-body">
                <p class="card-text"><?= nl2br(htmlspecialchars($post->text)) ?></p>
                <?php if (isset($_SESSION['user'])): ?>
                    <button class="btn btn-sm btn-primary add-comment-btn" data-post-id="<?= $post->id ?>">Добавить комментарий</button>
                <?php endif; ?>
                <div class="comments" id="comments-<?= $post->id ?>">
                    <!-- Здесь будут отображаться комментарии -->
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        
        <!-- Пагинация -->
        <?php if ($pages > 1): ?>
        <nav aria-label="Навигация по страницам блога">
            <ul class="pagination justify-content-center">
                <?php if ($current_page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?controller=blog&page=<?= $current_page - 1 ?>" aria-label="Предыдущая">
                        <span aria-hidden="true">&laquo;</span>
                        <span class="sr-only">Предыдущая</span>
                    </a>
                </li>
                <?php endif; ?>
                
                <?php
                // Показываем максимум 5 страниц вокруг текущей
                $start = max(1, $current_page - 2);
                $end = min($pages, $current_page + 2);
                
                if ($start > 1) {
                    echo '<li class="page-item"><a class="page-link" href="?controller=blog&page=1">1</a></li>';
                    if ($start > 2) {
                        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                    }
                }
                
                for ($i = $start; $i <= $end; $i++): ?>
                <li class="page-item <?= $i == $current_page ? 'active' : '' ?>">
                    <a class="page-link" href="?controller=blog&page=<?= $i ?>"><?= $i ?></a>
                </li>
                <?php endfor;
                
                if ($end < $pages) {
                    if ($end < $pages - 1) {
                        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                    }
                    echo '<li class="page-item"><a class="page-link" href="?controller=blog&page=' . $pages . '">' . $pages . '</a></li>';
                }
                ?>
                
                <?php if ($current_page < $pages): ?>
                <li class="page-item">
                    <a class="page-link" href="?controller=blog&page=<?= $current_page + 1 ?>" aria-label="Следующая">
                        <span aria-hidden="true">&raquo;</span>
                        <span class="sr-only">Следующая</span>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </nav>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php require_once 'views/footer.php'; ?>
<script src="public/js/blog-comments.js"></script>
