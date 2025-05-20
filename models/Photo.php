<?php
require_once __DIR__ . '/Model.php';

class Photo extends Model {
    protected static $tablename = 'photos';
    public static $pdo;
    protected $errors = [];
    
    public $id;
    public $filename;
    public $caption;
    public $uploaded_at;

    protected static function getDB() {
        if (!isset(self::$pdo)) {
            $dsn = 'mysql:host=localhost;dbname=web_db;charset=utf8';
            $username = 'root';
            $password = '';
            
            try {
                self::$pdo = new PDO($dsn, $username, $password);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(PDOException $e) {
                error_log($e->getMessage());
                throw new Exception('Ошибка подключения к базе данных');
            }
        }
        return self::$pdo;
    }
    
    public static function getAll() {
        try {
            // Если таблица не существует, используем фиксированный список
            if (!self::tableExists()) {
                return self::getDefaultPhotos();
            }
            
            return parent::findAll();
        } catch (Exception $e) {
            error_log($e->getMessage());
            return self::getDefaultPhotos();
        }
    }
    
    private static function getDefaultPhotos() {
        $photos = [];
        $photoDir = __DIR__ . '/../public/images/';
        
        // Поиск всех jpg файлов в директории
        $photoFiles = glob($photoDir . 'photo*.jpg');
        
        foreach ($photoFiles as $index => $file) {
            $photo = new self();
            $photo->id = $index + 1;
            $photo->filename = basename($file);
            $photo->caption = 'Фото №' . ($index + 1);
            $photo->uploaded_at = date('Y-m-d H:i:s');
            $photos[] = $photo;
        }
        
        return $photos;
    }
    
    protected static function tableExists() {
        try {
            self::getDB(); // Убедимся, что соединение установлено
            $sql = "SHOW TABLES LIKE '" . static::$tablename . "'";
            $result = static::$pdo->query($sql);
            return $result->rowCount() > 0;
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }
    
    public static function createTable() {
        try {
            self::getDB(); // Добавляем инициализацию подключения
            $sql = "CREATE TABLE IF NOT EXISTS " . static::$tablename . " (
                id INT AUTO_INCREMENT PRIMARY KEY,
                filename VARCHAR(255) NOT NULL,
                caption VARCHAR(255),
                uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";
            return static::$pdo->exec($sql) !== false;
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }
    
    public function validate($data) {
        $this->errors = [];
        
        if (empty($this->filename)) {
            $this->errors[] = "Имя файла не может быть пустым";
        }
        
        if (empty($this->caption)) {
            $this->errors[] = "Подпись к фото не может быть пустой";
        }
        
        return empty($this->errors);
    }
    
    public function getErrors() {
        return $this->errors;
    }
}