<footer class="footer mt-auto py-3">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <h5>О сайте</h5>
                    <p class="text-muted">
                        Этот сайт создан в рамках лабораторной работы №9 
                        по исследованию архитектуры MVC приложения
                    </p>
                </div>
                <div class="col-md-4 mb-3">
                    <h5>Навигация</h5>
                    <ul class="list-unstyled">
                        <li><a href="index.php?controller=blog">Блог</a></li>
                        <li><a href="index.php?controller=photo">Фотоальбом</a></li>
                        <li><a href="index.php?controller=interests">Интересы</a></li>
                        <li><a href="index.php?controller=contact">Контакты</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-3">
                    <h5>Контакты</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-envelope"></i> email@example.com</li>
                        <li><i class="fas fa-phone"></i> +7 (978) XXX-XX-XX</li>
                        <li><i class="fas fa-map-marker-alt"></i> г. Севастополь</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="row">
                <div class="col-12 text-center">
                    <p class="mb-0">© <?= date('Y') ?> Мой сайт. Все права защищены.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="public/js/script.js"></script>
</body>
</html>