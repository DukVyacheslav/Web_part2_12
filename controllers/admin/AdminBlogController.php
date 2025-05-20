<?php
require_once __DIR__ . '/../AdminController.php';
require_once __DIR__ . '/../../models/Blog.php';
require_once __DIR__ . '/../../views/View.php';

class AdminBlogController extends AdminController {
    private $model;
    private $view;

    public function __construct() {
        $this->model = new Blog();
        $this->view = new View();
    }

    public function editor() {
        $this->authenticate();
        $posts = Blog::findAll();
        $this->view->render('admin/blog/editor.php', 'Редактор блога', ['posts' => $posts]);
    }

    // Можно добавить методы save, upload и др. по необходимости
}
?>
