<?php
require_once __DIR__ . '/../models/Photo.php';
require_once __DIR__ . '/../views/View.php';

class PhotoController {
    private $view;
    
    public function __construct() {
        $this->view = new View();
    }
    
    public function index() {
        try {
            $photos = Photo::getAll();
            $this->view->render('photoalbum.php', 'Фотоальбом', [
                'photos' => $photos,
                'error' => null
            ]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->view->render('photoalbum.php', 'Фотоальбом', [
                'photos' => [],
                'error' => 'Произошла ошибка при загрузке фотографий'
            ]);
        }
    }
}