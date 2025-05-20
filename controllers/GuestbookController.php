<?php
require_once __DIR__.'/../models/Guestbook.php';
require_once __DIR__.'/../views/View.php';

require_once __DIR__ . '/../models/StatisticModel.php';

class GuestbookController {
    private $model;
    private $view;
    
    public function __construct() {
        $this->model = new Guestbook();
        $this->view = new View();

        $statistic = new StatisticModel();
        $statistic->save_statistic($_SERVER['REQUEST_URI']);
    }
    
    public function index() {
        $messages = Guestbook::findAll();
        $this->view->render('guestbook/index.php', 'Гостевая книга', ['messages' => $messages]);
    }
    
    public function save() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->model->validate($_POST)) {
                $this->model->date = date('Y-m-d H:i:s');
                $this->model->fio = $_POST['fio'];
                $this->model->email = $_POST['email'];
                $this->model->message = $_POST['message'];
                
                // Сохраняем в базу данных
                if ($this->model->save()) {
                    // Дополнительно сохраняем в файл
                    Guestbook::saveToFile($_POST);
                }
            }
        }
        header('Location: index.php?controller=guestbook');
    }
    
    public function upload() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['messages'])) {
            $file = $_FILES['messages'];
            if ($file['error'] === UPLOAD_ERR_OK) {
                $tempName = $file['tmp_name'];
                if (Guestbook::loadFromFile($tempName)) {
                    // Успешно загружено
                }
            }
        }
        $this->view->render('guestbook/upload.php', 'Загрузка сообщений гостевой книги');
    }
}
?>