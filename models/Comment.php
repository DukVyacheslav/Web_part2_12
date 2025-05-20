<?php
require_once 'Model.php';

class Comment extends Model {
    protected static $tablename = 'comments';
    protected static $fields = ['id', 'post_id', 'user_id', 'message', 'created_at'];

    public $id;
    public $post_id;
    public $user_id;
    public $message;
    public $created_at;

    public function validate($data) {
        return !empty($data['post_id']) && !empty($data['user_id']) && !empty($data['message']);
    }

    public function save() {
        static::setupConnection();
        $stmt = static::$pdo->prepare("INSERT INTO " . static::$tablename . " (post_id, user_id, message, created_at) VALUES (:post_id, :user_id, :message, :created_at)");
        $result = $stmt->execute([
            'post_id' => $this->post_id,
            'user_id' => $this->user_id,
            'message' => $this->message,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        if ($result) {
            $this->id = static::$pdo->lastInsertId();
            $this->created_at = date('Y-m-d H:i:s');
        }
        return $result;
    }

    public static function getByPostId($post_id) {
        static::setupConnection();
        $stmt = static::$pdo->prepare("SELECT c.*, u.fio FROM " . static::$tablename . " c JOIN users u ON c.user_id = u.id WHERE post_id = :post_id ORDER BY created_at DESC");
        $stmt->execute(['post_id' => $post_id]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    protected static function createTable() {
        static::setupConnection();
        $sql = "CREATE TABLE IF NOT EXISTS " . static::$tablename . " (
            id INT AUTO_INCREMENT PRIMARY KEY,
            post_id INT NOT NULL,
            user_id INT NOT NULL,
            message TEXT NOT NULL,
            created_at DATETIME NOT NULL,
            FOREIGN KEY (post_id) REFERENCES blog(id) ON DELETE CASCADE,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
        static::$pdo->exec($sql);
        return true;
    }
}
?>
