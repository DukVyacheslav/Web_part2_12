<?php
require_once 'Model.php';
require_once 'ResultsVerification.php';

class Guestbook extends Model {
    protected static $tablename = 'guestbook';
    protected $validator;
    protected static $db;
    
    public $id;
    public $date;
    public $fio;
    public $email;
    public $message;
    
    public function __construct() {
        parent::__construct();
        $this->validator = new ResultsVerification();
        $this->validator->SetRule('fio', 'IsNotEmpty');
        $this->validator->SetRule('email', 'IsEmail');
        $this->validator->SetRule('message', 'IsNotEmpty');
    }

    protected static function getDB() {
        if (!isset(self::$db)) {
            $dsn = 'mysql:host=localhost;dbname=web_db;charset=utf8';
            $username = 'root';
            $password = '';
            
            try {
                self::$db = new PDO($dsn, $username, $password);
                self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();
            }
        }
        return self::$db;
    }

    public static function createTable() {
        try {
            $db = self::getDB();
            $sql = "CREATE TABLE IF NOT EXISTS " . static::$tablename . " (
                id INT AUTO_INCREMENT PRIMARY KEY,
                date DATETIME NOT NULL,
                fio VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL,
                message TEXT NOT NULL
            )";
            return $db->exec($sql) !== false;
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    protected static function ensureTableExists() {
        try {
            $db = self::getDB();
            $stmt = $db->query("SHOW TABLES LIKE '" . static::$tablename . "'");
            if ($stmt->rowCount() === 0) {
                return self::createTable();
            }
            return true;
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }
    
    public function validate($post_data) {
        $this->validator->validate($post_data);
        if (count($this->validator->errors) > 0) {
            return false;
        }
        return true;
    }

    public function save() {
        if (!isset($this->date)) {
            $this->date = date('Y-m-d H:i:s');
        }
        self::ensureTableExists();
        return parent::save();
    }

    public static function getAll() {
        self::ensureTableExists();
        $db = static::getDB();
        $stmt = $db->query('SELECT * FROM ' . static::$tablename . ' ORDER BY date DESC');
        return $stmt->fetchAll(PDO::FETCH_CLASS, get_called_class());
    }

    public function delete() {
        if (!isset($this->id)) {
            return false;
        }
        $db = static::getDB();
        $sql = 'DELETE FROM ' . static::$tablename . ' WHERE id = :id';
        $stmt = $db->prepare($sql);
        return $stmt->execute(['id' => $this->id]);
    }

    public function getErrors() {
        return $this->validator->errors;
    }

    public static function saveToFile($data) {
        $filename = __DIR__ . '/../data/guestbook.txt';
        $dir = dirname($filename);
        
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        
        $content = date('Y-m-d H:i:s') . "\t" . 
                   $data['fio'] . "\t" . 
                   $data['email'] . "\t" . 
                   $data['message'] . "\n";
                   
        return file_put_contents($filename, $content, FILE_APPEND);
    }
    
    public static function loadFromFile($filename) {
        if (!file_exists($filename)) {
            return false;
        }
        
        $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $success = true;
        
        foreach ($lines as $line) {
            $data = explode("\t", $line);
            if (count($data) === 4) {
                $message = new self();
                $message->date = $data[0];
                $message->fio = $data[1];
                $message->email = $data[2];
                $message->message = $data[3];
                
                if (!$message->save()) {
                    $success = false;
                }
            }
        }
        
        return $success;
    }
}
?>