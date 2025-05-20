<?php
require_once __DIR__.'/../models/TestResult.php';
require_once __DIR__.'/../views/View.php';

require_once __DIR__ . '/../models/StatisticModel.php';

class TestController {
    private $model;
    private $view;
    
    public function __construct() {
        $this->model = new TestResult();
        $this->view = new View();

        // Проверка авторизации пользователя
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?controller=user&action=login');
            exit;
        }

        $statistic = new StatisticModel();
        $statistic->save_statistic($_SERVER['REQUEST_URI']);
    }
    
    public function index() {
        try {
            $results = TestResult::findAll();
            $this->view->render('test.php', 'Тест по дисциплине', [
                'results' => $results,
                'error' => null
            ]);
        } catch (Exception $e) {
            $this->view->render('test.php', 'Тест по дисциплине', [
                'results' => [],
                'error' => 'Произошла ошибка при загрузке теста'
            ]);
        }
    }
    
    private function getCorrectAnswers() {
        // Пример правильных ответов, ключ - вопрос, значение - правильный ответ
        return [
            'q1' => 'a',
            'q2' => 'b',
            'q3' => 'c',
        ];
    }

    public function save() {
        $errors = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                if ($this->model->validate($_POST)) {
                    $userAnswers = $_POST['answers'];
                    $correctAnswers = $this->getCorrectAnswers();
                    $isCorrect = true;
                    foreach ($correctAnswers as $question => $correctAnswer) {
                        if (!isset($userAnswers[$question]) || $userAnswers[$question] !== $correctAnswer) {
                            $isCorrect = false;
                            break;
                        }
                    }
                    
                    $this->model->date = date('Y-m-d H:i:s');
                    $this->model->fio = $_POST['fio'];
                    $this->model->answers = json_encode($userAnswers);
                    $this->model->is_correct = $isCorrect ? 1 : 0;
                    
                    if (!$this->model->save()) {
                        $errors[] = 'Ошибка при сохранении результатов теста';
                    }
                } else {
                    $errors[] = 'Пожалуйста, заполните все поля корректно';
                }
            } catch (Exception $e) {
                error_log($e->getMessage());
                $errors[] = 'Произошла ошибка при сохранении результатов';
            }
        }
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header('Location: index.php?controller=test&status=error');
        } else {
            $_SESSION['success'] = 'Результаты теста успешно сохранены';
            header('Location: index.php?controller=test&status=success');
        }
        exit;
    }
    
    public function results() {
        try {
            $results = TestResult::findAll();
            $this->view->render('test/results.php', 'Результаты тестов', [
                'results' => $results,
                'error' => null
            ]);
        } catch (Exception $e) {
            $this->view->render('test/results.php', 'Результаты тестов', [
                'results' => [],
                'error' => 'Произошла ошибка при загрузке результатов тестов'
            ]);
        }
    }
}
?>