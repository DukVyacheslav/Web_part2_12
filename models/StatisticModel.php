<?php
require_once 'Model.php';

class StatisticModel extends Model {
    protected static $tablename = 'statistics';
    protected static $fields = ['id', 'time_statistic', 'web_page', 'ip_address', 'host_name', 'browser_name'];

    public $id;
    public $time_statistic;
    public $web_page;
    public $ip_address;
    public $host_name;
    public $browser_name;

    protected static function createTable() {
        static::setupConnection();
        $sql = "CREATE TABLE IF NOT EXISTS " . static::$tablename . " (
            id INT AUTO_INCREMENT PRIMARY KEY,
            time_statistic DATETIME NOT NULL,
            web_page VARCHAR(255) NOT NULL,
            ip_address VARCHAR(45) NOT NULL,
            host_name VARCHAR(255) NOT NULL,
            browser_name VARCHAR(255) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
        static::$pdo->exec($sql);
        return true;
    }

    public function save_statistic($page) {
        static::ensureTableExists();
        $this->time_statistic = date('Y-m-d H:i:s');
        $this->web_page = $page;
        $this->ip_address = $_SERVER['REMOTE_ADDR'];
        $this->host_name = gethostbyaddr($_SERVER['REMOTE_ADDR']);
        $this->browser_name = $_SERVER['HTTP_USER_AGENT'];
        return $this->save();
    }

    public static function getPaginated($page = 1, $per_page = 10) {
        $offset = ($page - 1) * $per_page;
        $db = static::$pdo;
        $stmt = $db->prepare("SELECT * FROM " . static::$tablename . " ORDER BY time_statistic DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $totalStmt = $db->query("SELECT COUNT(*) FROM " . static::$tablename);
        $total = $totalStmt->fetchColumn();

        $pages = ceil($total / $per_page);

        return [
            'items' => $items,
            'total' => $total,
            'pages' => $pages,
            'current_page' => $page
        ];
    }
}
?>
