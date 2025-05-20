<?php
class Model {
    public static $pdo;
    protected static $tablename;
    protected static $dbfields = array();
    protected $id;
    
    public function __construct() {
        if (!static::$tablename) {
            return;
        }
        static::setupConnection();
        static::getFields();
    }

    public static function getFields() {
        try {
            if (!static::$pdo) {
                throw new Exception('Отсутствует подключение к базе данных');
            }
            
            if (static::tableExists()) {
                $stmt = static::$pdo->query("SHOW FIELDS FROM " . static::$tablename);
                while ($row = $stmt->fetch()) {
                    static::$dbfields[$row['Field']] = $row['Type'];
                }
            }
        } catch (Exception $e) {
            error_log("Ошибка получения полей таблицы: " . $e->getMessage());
            throw $e;
        }
    }

    public static function setupConnection() {
        if (!isset(static::$pdo)) {
            try {
                // Сначала подключаемся без указания базы данных
                $pdo = new PDO(
                    "mysql:host=localhost;charset=utf8",
                    "root",
                    "",
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                );
                
                // Проверяем существование базы данных
                $stmt = $pdo->query("SHOW DATABASES LIKE 'web_db'");
                if ($stmt->rowCount() === 0) {
                    // Создаем базу данных, если она не существует
                    $pdo->exec("CREATE DATABASE web_db CHARACTER SET utf8 COLLATE utf8_general_ci");
                }
                
                // Подключаемся к созданной/существующей базе данных
                static::$pdo = new PDO(
                    "mysql:dbname=web_db;host=localhost;charset=utf8",
                    "root",
                    "",
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                );
            } catch (PDOException $e) {
                error_log("Ошибка подключения к БД: " . $e->getMessage());
                throw new Exception("Ошибка подключения к базе данных");
            }
        }
    }

    protected static function tableExists() {
        try {
            $stmt = static::$pdo->query("SHOW TABLES LIKE '" . static::$tablename . "'");
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    protected static function createTable() {
        return true; // Переопределяется в дочерних классах
    }

    protected static function ensureTableExists() {
        if (!static::tableExists()) {
            return static::createTable();
        }
        return true;
    }

    public static function find($id) {
        static::setupConnection();
        static::ensureTableExists();
        
        $sql = "SELECT * FROM " . static::$tablename . " WHERE id=:id";
        $stmt = static::$pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$row) {
            return null;
        }
        
        $ar_obj = new static();
        foreach ($row as $key => $value) {
            $ar_obj->$key = $value;
        }
        return $ar_obj;
    }

    public static function findAll() {
        static::setupConnection();
        if (!static::ensureTableExists()) {
            return [];
        }

        $sql = "SELECT * FROM " . static::$tablename;
        $stmt = static::$pdo->prepare($sql);
        $stmt->execute();
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $obj = new static();
            foreach ($row as $key => $value) {
                $obj->$key = $value;
            }
            $result[] = $obj;
        }
        return $result;
    }

    public function save() {
        static::setupConnection();
        static::ensureTableExists();
        
        if (isset($this->id)) {
            return $this->update();
        } else {
            return $this->insert();
        }
    }

    protected function insert() {
        $fields = array();
        $values = array();
        $params = array();
        
        foreach (static::$dbfields as $field => $type) {
            if ($field !== 'id' && isset($this->$field)) {
                $fields[] = $field;
                $values[] = ':' . $field;
                $params[':' . $field] = $this->$field;
            }
        }
        
        $sql = "INSERT INTO " . static::$tablename . " (" . implode(',', $fields) . ") 
                VALUES (" . implode(',', $values) . ")";
        
        $stmt = static::$pdo->prepare($sql);
        $result = $stmt->execute($params);
        if ($result) {
            $this->id = static::$pdo->lastInsertId();
        }
        return $result;
    }

    protected function update() {
        $fields = array();
        $params = array();
        
        foreach (static::$dbfields as $field => $type) {
            if ($field !== 'id' && isset($this->$field)) {
                $fields[] = "$field = :$field";
                $params[':' . $field] = $this->$field;
            }
        }
        
        $params[':id'] = $this->id;
        $sql = "UPDATE " . static::$tablename . " SET " . implode(',', $fields) . " WHERE id = :id";
        
        $stmt = static::$pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete() {
        static::setupConnection();
        if (!isset($this->id)) {
            return false;
        }
        
        $sql = "DELETE FROM " . static::$tablename . " WHERE id = :id";
        $stmt = static::$pdo->prepare($sql);
        return $stmt->execute([':id' => $this->id]);
    }

    protected function handleDatabaseError($e) {
        error_log($e->getMessage());
        throw new Exception('Произошла ошибка при работе с базой данных');
    }

    public function validate($data) {
        return true; // Переопределяется в дочерних классах
    }
}
?>