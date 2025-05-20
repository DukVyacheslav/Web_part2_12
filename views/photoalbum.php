<?php require_once 'views/header.php'; ?>

<div class="container">
    <h1>Фотоальбом</h1>
    
    <?php if (isset($error)): ?>
    <div class="alert alert-danger">
        <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>
    
    <div class="row photo-grid">
        <?php foreach ($photos as $photo): ?>
        <div class="col">
            <div class="card">
                <img src="public/images/<?= htmlspecialchars($photo->filename) ?>" 
                     alt="<?= htmlspecialchars($photo->caption) ?>" 
                     class="card-img-top"
                     data-toggle="modal"
                     data-target="#photoModal"
                     data-caption="<?= htmlspecialchars($photo->caption) ?>">
                <div class="card-body">
                    <p class="card-text"><?= htmlspecialchars($photo->caption) ?></p>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Модальное окно для просмотра фотографий -->
<div class="modal fade" id="photoModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img src="" class="img-fluid" alt="">
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Открытие фото в модальном окне
    $('.card-img-top').click(function() {
        const src = $(this).attr('src');
        const caption = $(this).data('caption');
        
        $('#photoModal').find('.modal-title').text(caption);
        $('#photoModal').find('.modal-body img').attr('src', src);
    });

    // Навигация по фото с помощью стрелок
    $(document).keydown(function(e) {
        if ($('#photoModal').is(':visible')) {
            const currentImg = $('#photoModal').find('.modal-body img').attr('src');
            const images = $('.card-img-top');
            let currentIndex = -1;
            
            images.each(function(index) {
                if ($(this).attr('src') === currentImg) {
                    currentIndex = index;
                    return false;
                }
            });

            if (e.key === 'ArrowLeft' && currentIndex > 0) {
                const prevImg = images.eq(currentIndex - 1);
                $('#photoModal').find('.modal-title').text(prevImg.data('caption'));
                $('#photoModal').find('.modal-body img').attr('src', prevImg.attr('src'));
            } else if (e.key === 'ArrowRight' && currentIndex < images.length - 1) {
                const nextImg = images.eq(currentIndex + 1);
                $('#photoModal').find('.modal-title').text(nextImg.data('caption'));
                $('#photoModal').find('.modal-body img').attr('src', nextImg.attr('src'));
            }
        }
    });
});
</script>

<?php require_once 'views/footer.php'; ?>