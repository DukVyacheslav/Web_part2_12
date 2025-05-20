<?php
require_once 'Model.php';

class User extends Model {
    protected static $tablename = 'users';
    protected static $fields = ['id', 'fio', 'email', 'login', 'password'];

    public $id;
    public $fio;
    public $email;
    public $login;
    public $password;

    public function validate($data) {
        return !empty($data['fio']) && !empty($data['email']) && !empty($data['login']) && !empty($data['password']);
    }

    public function isLoginExists($login) {
        static::setupConnection();
        $stmt = static::$pdo->prepare("SELECT COUNT(*) FROM " . static::$tablename . " WHERE login = :login");
        $stmt->execute(['login' => $login]);
        return $stmt->fetchColumn() > 0;
    }

    public function findByLoginAndPassword($login, $password) {
        static::setupConnection();
        $stmt = static::$pdo->prepare("SELECT * FROM " . static::$tablename . " WHERE login = :login AND password = :password");
        $stmt->execute(['login' => $login, 'password' => $password]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        $user = new self();
        foreach ($row as $key => $value) {
            $user->$key = $value;
        }
        return $user;
    }

    protected static function createTable() {
        static::setupConnection();
        $sql = "CREATE TABLE IF NOT EXISTS " . static::$tablename . " (
            id INT AUTO_INCREMENT PRIMARY KEY,
            fio VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            login VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
        static::$pdo->exec($sql);
        return true;
    }
}
?>
