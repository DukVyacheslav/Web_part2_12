document.addEventListener('DOMContentLoaded', () => {
    const lightbox = document.getElementById('lightbox');
    const lightboxImage = document.getElementById('lightbox-image');
    const lightboxCaption = document.getElementById('lightbox-caption');
    const closeLightbox = document.getElementById('close-lightbox');
    const photoItems = document.querySelectorAll('.photo-item');
    let currentIndex = -1; // Изначально не выбрано ни одно фото
    const totalPhotos = 15; // Общее количество фото

    // Убедимся, что модальное окно изначально скрыто
    lightbox.style.display = 'none';

    // Открытие модального окна при клике на фото
    photoItems.forEach(item => {
        item.addEventListener('click', () => {
            currentIndex = parseInt(item.getAttribute('data-index'));
            updateLightbox();
            lightbox.style.display = 'block';
            console.log('Modal opened, currentIndex:', currentIndex); // Отладка
        });
    });

    // Закрытие модального окна
    closeLightbox.addEventListener('click', () => {
        lightbox.style.display = 'none';
        currentIndex = -1; // Сбрасываем индекс при закрытии
        console.log('Modal closed'); // Отладка
    });

    // Закрытие при клике вне изображения
    lightbox.addEventListener('click', (e) => {
        if (e.target === lightbox) {
            lightbox.style.display = 'none';
            currentIndex = -1; // Сбрасываем индекс при закрытии
            console.log('Modal closed by background click'); // Отладка
        }
    });

    // Листание стрелками
    document.addEventListener('keydown', (e) => {
        if (lightbox.style.display === 'block' && currentIndex !== -1) {
            if (e.key === 'ArrowLeft' && currentIndex > 0) {
                currentIndex--;
                updateLightbox();
                console.log('Left arrow, currentIndex:', currentIndex); // Отладка
            } else if (e.key === 'ArrowRight' && currentIndex < totalPhotos - 1) {
                currentIndex++;
                updateLightbox();
                console.log('Right arrow, currentIndex:', currentIndex); // Отладка
            }
        }
    });

    // Обновление содержимого модального окна
    function updateLightbox() {
        if (currentIndex >= 0) {
            const file = `photo${currentIndex + 1}.jpg`;
            const caption = `Фото №${currentIndex + 1}`;
            lightboxImage.src = `/myproject/public/images/${file}`;
            lightboxCaption.textContent = caption;
        }
    }
});

// Инициализация всех всплывающих подсказок
$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();
    
    // Анимация для карточек при прокрутке
    function animateOnScroll() {
        $('.card').each(function() {
            const cardPosition = $(this).offset().top;
            const scrollPosition = $(window).scrollTop();
            const windowHeight = $(window).height();
            
            if (cardPosition < scrollPosition + windowHeight - 100) {
                $(this).addClass('fade-in');
            }
        });
    }
    
    $(window).on('scroll', animateOnScroll);
    animateOnScroll();
    
    // Обработка фотогалереи
    const photoModal = $('#photoModal');
    const modalImage = photoModal.find('.modal-body img');
    const modalTitle = photoModal.find('.modal-title');
    let currentPhotoIndex = 0;
    const totalPhotos = $('.photo-item').length;

    // Открытие фото в модальном окне
    $('.photo-item').click(function() {
        const src = $(this).attr('src');
        const caption = $(this).data('caption');
        currentPhotoIndex = $('.photo-item').index(this);
        
        modalImage.attr('src', src);
        modalTitle.text(caption);
        photoModal.modal('show');
    });

    // Навигация по фото с помощью клавиш
    $(document).keydown(function(e) {
        if (!photoModal.is(':visible')) return;
        
        if (e.key === 'ArrowLeft' && currentPhotoIndex > 0) {
            currentPhotoIndex--;
            updateModalPhoto();
        } else if (e.key === 'ArrowRight' && currentPhotoIndex < totalPhotos - 1) {
            currentPhotoIndex++;
            updateModalPhoto();
        } else if (e.key === 'Escape') {
            photoModal.modal('hide');
        }
    });

    function updateModalPhoto() {
        const newPhoto = $('.photo-item').eq(currentPhotoIndex);
        modalImage.attr('src', newPhoto.attr('src'));
        modalTitle.text(newPhoto.data('caption'));
    }

    // Валидация форм
    $('form').on('submit', function(e) {
        const form = $(this);
        if (!form[0].checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        form.addClass('was-validated');
    });

    // Автоматическое обновление высоты textarea
    $('textarea').on('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });

    // Сохранение данных форм в localStorage
    $('form :input').on('input', function() {
        const form = $(this).closest('form');
        const formId = form.attr('id');
        if (formId) {
            const formData = {};
            form.find(':input').each(function() {
                const input = $(this);
                if (input.attr('type') !== 'submit' && input.attr('type') !== 'password') {
                    formData[input.attr('name')] = input.val();
                }
            });
            localStorage.setItem('form_' + formId, JSON.stringify(formData));
        }
    });

    // Восстановление данных форм из localStorage
    $('form').each(function() {
        const form = $(this);
        const formId = form.attr('id');
        if (formId) {
            const savedData = localStorage.getItem('form_' + formId);
            if (savedData) {
                const formData = JSON.parse(savedData);
                for (const field in formData) {
                    form.find(`[name="${field}"]`).val(formData[field]);
                }
            }
        }
    });

    // Анимация для навигационных элементов
    $('.nav-link').hover(
        function() { $(this).addClass('fade-in'); },
        function() { $(this).removeClass('fade-in'); }
    );

    // Плавная прокрутка для якорных ссылок
    $('a[href^="#"]').on('click', function(e) {
        e.preventDefault();
        const target = $(this.hash);
        if (target.length) {
            $('html, body').animate({
                scrollTop: target.offset().top - 70
            }, 500);
        }
    });

    // Обработка ошибок загрузки изображений
    $('img').on('error', function() {
        $(this).attr('src', 'public/images/placeholder.jpg');
    });
});