<?php
require_once __DIR__ . '/../models/FormValidation.php';

class ContactController {
    private $validator;

    public function __construct() {
        $this->validator = new FormValidation();
        // Устанавливаем правила валидации
        $this->validator->setRule('name', 'required');
        $this->validator->setRule('email', 'required');
        $this->validator->setRule('email', 'email');
        $this->validator->setRule('phone', 'required');
        $this->validator->setRule('birthdate', 'required');
        $this->validator->setRule('age', 'required');
        $this->validator->setRule('gender', 'required');
    }

    public function index() {
        $data = [
            'title' => 'Контакты',
            'error_message' => '',
            'success_message' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->validator->validate($_POST)) {
                $data['success_message'] = "Форма успешно отправлена!";
            } else {
                $data['error_message'] = "Пожалуйста, заполните все обязательные поля корректно.";
            }
        }

        require_once __DIR__ . '/../views/contact.php';
    }
}
?>