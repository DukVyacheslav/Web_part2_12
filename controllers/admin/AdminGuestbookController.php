<?php
require_once __DIR__ . '/../AdminController.php';
require_once __DIR__ . '/../../models/Guestbook.php';
require_once __DIR__ . '/../../views/View.php';

class AdminGuestbookController extends AdminController {
    private $model;
    private $view;

    public function __construct() {
        $this->model = new Guestbook();
        $this->view = new View();
    }

    public function upload() {
        $this->authenticate();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['messages'])) {
            $file = $_FILES['messages'];
            if ($file['error'] === UPLOAD_ERR_OK) {
                $tempName = $file['tmp_name'];
                if (Guestbook::loadFromFile($tempName)) {
                    $_SESSION['success'] = 'Сообщения успешно загружены';
                } else {
                    $_SESSION['errors'] = ['Ошибка при загрузке сообщений из файла'];
                }
            } else {
                $_SESSION['errors'] = ['Ошибка при загрузке файла'];
            }
            header('Location: index.php?controller=admin_guestbook&action=upload');
            exit;
        }
        $this->view->render('admin/guestbook/upload.php', 'Загрузка сообщений гостевой книги');
    }
}
?>
