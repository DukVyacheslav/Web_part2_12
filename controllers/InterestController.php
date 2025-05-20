<?php
require_once __DIR__ . '/../models/Interest.php';
require_once __DIR__ . '/../views/View.php';

class InterestController {
    private $view;
    
    public function __construct() {
        $this->view = new View();
    }
    
    public function index() {
        try {
            $interests = Interest::getAll();
            $descriptions = Interest::getDescriptions();
            
            if (empty($interests)) {
                throw new Exception('Не удалось загрузить список интересов');
            }
            
            $this->view->render('interests.php', 'Мои интересы', [
                'interests' => $interests,
                'descriptions' => $descriptions,
                'error' => null
            ]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->view->render('interests.php', 'Мои интересы', [
                'interests' => [],
                'descriptions' => [],
                'error' => 'Произошла ошибка при загрузке интересов'
            ]);
        }
    }
}