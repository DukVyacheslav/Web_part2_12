<?php
require_once __DIR__.'/../models/Blog.php';
require_once __DIR__.'/../views/View.php';

require_once __DIR__ . '/../models/StatisticModel.php';

class BlogController {
    private $model;
    private $view;
    private $allowedImageTypes = ['image/jpeg', 'image/png', 'image/gif'];
    private $maxFileSize = 5242880; // 5MB
    
    public function __construct() {
        $this->model = new Blog();
        $this->view = new View();

        $statistic = new StatisticModel();
        $statistic->save_statistic($_SERVER['REQUEST_URI']);
    }
    
    public function index() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $per_page = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;
        
        $result = Blog::getPaginated($page, $per_page);
        // Удаляем временный вывод var_dump
        $this->view->render('blog/index.php', 'Мой блог', [
            'posts' => $result['items'],
            'total' => $result['total'],
            'pages' => $result['pages'],
            'current_page' => $page
        ]);
    }
    
    public function editor() {
        $posts = Blog::findAll();
        $this->view->render('blog/editor.php', 'Редактор блога', ['posts' => $posts]);
    }
    
    public function save() {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->model->validate($_POST)) {
                $this->model->name = $_POST['name'];
                $this->model->text = $_POST['text'];
                $this->model->data = date('Y-m-d H:i:s');
                
                // Обработка загруженного изображения
                if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
                    if (!in_array($_FILES['img']['type'], $this->allowedImageTypes)) {
                        $errors[] = 'Недопустимый тип файла. Разрешены только JPEG, PNG и GIF.';
                    } elseif ($_FILES['img']['size'] > $this->maxFileSize) {
                        $errors[] = 'Размер файла превышает 5MB.';
                    } else {
                        $uploadDir = 'public/images/blog/';
                        if (!file_exists($uploadDir)) {
                            if (!mkdir($uploadDir, 0777, true)) {
                                $errors[] = 'Ошибка создания директории для загрузки.';
                            }
                        }
                        
                        if (empty($errors)) {
                            $fileName = uniqid() . '_' . htmlspecialchars($_FILES['img']['name']);
                            $filePath = $uploadDir . $fileName;
                            
                            if (move_uploaded_file($_FILES['img']['tmp_name'], $filePath)) {
                                $this->model->img = $fileName;
                            } else {
                                $errors[] = 'Ошибка при загрузке изображения.';
                            }
                        }
                    }
                }
                
                if (empty($errors)) {
                    if (!$this->model->save()) {
                        $errors[] = 'Ошибка при сохранении записи в базу данных.';
                    }
                }
            } else {
                $errors[] = 'Пожалуйста, проверьте правильность заполнения всех полей.';
            }
        }
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header('Location: index.php?controller=blog&action=editor&status=error');
        } else {
            $_SESSION['success'] = 'Запись успешно сохранена.';
            header('Location: index.php?controller=blog&action=editor&status=success');
        }
        exit;
    }
    
    public function upload() {
        // Обработка POST-запроса загрузки CSV файла
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file'])) {
            $errors = [];
            $file = $_FILES['csv_file'];
            if ($file['error'] === UPLOAD_ERR_OK) {
                // Проверка типа файла
                $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
                $mimeType = finfo_file($fileInfo, $file['tmp_name']);
                finfo_close($fileInfo);
                
                if ($mimeType !== 'text/csv' && $mimeType !== 'application/vnd.ms-excel') {
                    $errors[] = 'Пожалуйста, загрузите файл в формате CSV.';
                } elseif ($file['size'] > $this->maxFileSize) {
                    $errors[] = 'Размер файла превышает 5MB.';
                } else {
                    $tempName = $file['tmp_name'];
                    if (!Blog::importFromCSV($tempName)) {
                        $errors[] = 'Ошибка при импорте данных из CSV файла.';
                    }
                }
            } else {
                $errors[] = 'Ошибка при загрузке файла: ' . $this->getFileErrorMessage($file['error']);
            }
            
            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
            } else if (isset($tempName)) {
                $_SESSION['success'] = 'Данные успешно импортированы из CSV файла.';
            }
            header('Location: index.php?controller=blog&action=showUploadForm');
            exit;
        } else {
            // Если не POST, перенаправляем на форму загрузки
            header('Location: index.php?controller=blog&action=showUploadForm');
            exit;
        }
    }

    public function showUploadForm() {
        $this->view->render('blog/upload.php', 'Загрузка сообщений блога');
    }
    
    public function edit() {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $post = Blog::findById($_POST['id']);
            if (!$post) {
                $errors[] = 'Запись не найдена.';
            } else {
                if ($this->model->validate($_POST)) {
                    $post->name = $_POST['name'];
                    $post->text = $_POST['text'];
                    $post->data = date('Y-m-d H:i:s');
                    
                    // Обработка загруженного изображения
                    if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
                        if (!in_array($_FILES['img']['type'], $this->allowedImageTypes)) {
                            $errors[] = 'Недопустимый тип файла. Разрешены только JPEG, PNG и GIF.';
                        } elseif ($_FILES['img']['size'] > $this->maxFileSize) {
                            $errors[] = 'Размер файла превышает 5MB.';
                        } else {
                            $uploadDir = 'public/images/blog/';
                            if (!file_exists($uploadDir)) {
                                if (!mkdir($uploadDir, 0777, true)) {
                                    $errors[] = 'Ошибка создания директории для загрузки.';
                                }
                            }
                            
                            if (empty($errors)) {
                                $fileName = uniqid() . '_' . htmlspecialchars($_FILES['img']['name']);
                                $filePath = $uploadDir . $fileName;
                                
                                if (move_uploaded_file($_FILES['img']['tmp_name'], $filePath)) {
                                    $post->img = $fileName;
                                } else {
                                    $errors[] = 'Ошибка при загрузке изображения.';
                                }
                            }
                        }
                    }
                    
                    if (empty($errors)) {
                        if (!$post->save()) {
                            $errors[] = 'Ошибка при сохранении записи в базу данных.';
                        }
                    }
                } else {
                    $errors[] = 'Пожалуйста, проверьте правильность заполнения всех полей.';
                }
            }
        } else {
            $errors[] = 'Неверный запрос.';
        }
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header('Location: index.php?controller=blog&action=editor&status=error');
        } else {
            $_SESSION['success'] = 'Запись успешно обновлена.';
            header('Location: index.php?controller=blog&action=editor&status=success');
        }
        exit;
    }
    
    public function delete() {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $post = Blog::findById($_POST['id']);
            if (!$post) {
                $errors[] = 'Запись не найдена.';
            } else {
                if (!$post->delete()) {
                    $errors[] = 'Ошибка при удалении записи.';
                }
            }
        } else {
            $errors[] = 'Неверный запрос.';
        }
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header('Location: index.php?controller=blog&action=editor&status=error');
        } else {
            $_SESSION['success'] = 'Запись успешно удалена.';
            header('Location: index.php?controller=blog&action=editor&status=success');
        }
        exit;
    }

    public function addComment() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            if (!isset($_SESSION['user'])) {
                http_response_code(403);
                echo json_encode(['error' => 'Пользователь не авторизован']);
                exit;
            }
            $userId = $_SESSION['user']['id'] ?? null;
            $postId = $_POST['post_id'] ?? null;
            $message = trim($_POST['message'] ?? '');

            if (!$postId || !$message) {
                http_response_code(400);
                echo json_encode(['error' => 'Неверные данные']);
                exit;
            }

            require_once __DIR__ . '/../models/Comment.php';
            $comment = new Comment();
            $comment->post_id = $postId;
            $comment->user_id = $userId;
            $comment->message = $message;

            if ($comment->save()) {
                echo json_encode([
                    'success' => true,
                    'comment' => [
                        'id' => $comment->id,
                        'post_id' => $comment->post_id,
                        'user_id' => $comment->user_id,
                        'message' => htmlspecialchars($comment->message),
                        'created_at' => $comment->created_at,
                        'user_fio' => $_SESSION['user']['fio'] ?? 'Автор'
                    ]
                ]);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Ошибка при сохранении комментария']);
            }
            exit;
        }
    }

    public function getComments() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $postId = $_GET['post_id'] ?? null;
            if (!$postId) {
                http_response_code(400);
                echo json_encode(['error' => 'Неверный ID записи']);
                exit;
            }
            require_once __DIR__ . '/../models/Comment.php';
            $comments = Comment::getByPostId($postId);
            echo json_encode(['comments' => $comments]);
            exit;
        }
    }
    
    private function getFileErrorMessage($code) {
        switch ($code) {
            case UPLOAD_ERR_INI_SIZE:
                return 'Размер файла превышает допустимый размер.';
            case UPLOAD_ERR_FORM_SIZE:
                return 'Размер файла превышает указанный в форме.';
            case UPLOAD_ERR_PARTIAL:
                return 'Файл был загружен частично.';
            case UPLOAD_ERR_NO_FILE:
                return 'Файл не был загружен.';
            case UPLOAD_ERR_NO_TMP_DIR:
                return 'Отсутствует временная папка.';
            case UPLOAD_ERR_CANT_WRITE:
                return 'Ошибка записи файла на диск.';
            case UPLOAD_ERR_EXTENSION:
                return 'Загрузка файла была остановлена расширением PHP.';
            default:
                return 'Неизвестная ошибка при загрузке файла.';
        }
    }
}
?>