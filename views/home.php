<?php require_once 'views/header.php'; ?>

<div class="container">
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 text-center">
                    <img src="public/images/photo.jpg" alt="Фото Вячеслава Николаевича" class="img-fluid rounded mb-3" style="max-width: 300px;">
                    <div id="clock" class="h5 mb-3"></div>
                </div>
                <div class="col-md-8">
                    <h1 class="card-title">Дук Вячеслав Николаевич</h1>
                    <p class="card-text">
                        Это главная страница моего сайта, созданного в рамках ЛАБОРАТОРНАЯ РАБОТА №9
                        "Исследование возможностей хранения данных на стороне сервера.
                        Работа с файлами. Работа с реляционными СУБД"
                    </p>
                    
                    <h2>Моя автобиография:</h2>
                    <p class="card-text">
                        Здравствуйте! Меня зовут Дук Вячеслав Николаевич. Я родился 25 июля 1983 года в городе Севастополе.
                        С детства я увлекался технологиями. Это привело меня к выбору профессии в области машиностроения.
                    </p>
                    
                    <p class="card-text">
                        Я окончил СевГУ по специальности Технология Машиностроения. После окончания учебы, 
                        я начал работать в компании Таврида Электрик.
                    </p>
                    
                    <h3>Профессиональные достижения:</h3>
                    <ul class="list-group list-group-flush mb-3">
                        <li class="list-group-item">Освоил профессию оператора станков с ПУ</li>
                        <li class="list-group-item">Освоил профессию мастера участка</li>
                        <li class="list-group-item">Освоил профессию технолога</li>
                    </ul>
                    
                    <p class="card-text">
                        В свободное время я люблю читать, играть в приставку. 
                        Это помогает мне расслабиться и получить новые идеи для работы.
                    </p>
                    
                    <blockquote class="blockquote mt-3">
                        <p class="mb-0">
                            Моя жизненная философия: Счастье приходит, когда вы перестаёте ждать, 
                            что оно придёт само, и предпринимаете шаги, чтобы оно пришло.
                        </p>
                    </blockquote>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateClock() {
    const now = new Date();
    const options = { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric', 
        hour: '2-digit', 
        minute: '2-digit', 
        second: '2-digit' 
    };
    document.getElementById('clock').textContent = now.toLocaleDateString('ru-RU', options);
}
setInterval(updateClock, 1000);
updateClock();
</script>

<?php require_once 'views/footer.php'; ?>