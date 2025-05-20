<?php
require_once 'Model.php';
require_once 'ResultsVerification.php';

class TestResult extends Model {
    protected static $tablename = 'test_results';
    protected $validator;
    
    public $id;
    public $date;
    public $fio;
    public $answers;
    public $is_correct;
    
    public function __construct() {
        parent::__construct();
        $this->validator = new ResultsVerification();
        $this->validator->setRule('fio', 'notEmpty');
    }
    
    public static function createTable() {
        try {
            static::setupConnection();
            $sql = "CREATE TABLE IF NOT EXISTS " . static::$tablename . " (
                id INT AUTO_INCREMENT PRIMARY KEY,
                date DATETIME NOT NULL,
                fio VARCHAR(255) NOT NULL,
                answers TEXT NOT NULL,
                is_correct TINYINT(1) DEFAULT 0
            )";
            return static::$pdo->exec($sql) !== false;
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }
    
    public function validate($post_data) {
        if (empty($post_data['fio'])) {
            return false;
        }
        
        if (!isset($post_data['answers']) || !is_array($post_data['answers']) || empty($post_data['answers'])) {
            return false;
        }
        
        return true;
    }
}
?>