<?php

class CreateBlogsTable
{
    public static function up($pdo)
    {
        $sql = "CREATE TABLE IF NOT EXISTS blogs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            text TEXT NOT NULL,
            img VARCHAR(255) DEFAULT NULL,
            data DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8";

        try {
            $pdo->exec($sql);
        } catch (PDOException $e) {
            error_log("Ошибка создания таблицы blogs: " . $e->getMessage());
            throw $e;
        }
    }

    public static function down($pdo)
    {
        $sql = "DROP TABLE IF EXISTS blogs";

        try {
            $pdo->exec($sql);
        } catch (PDOException $e) {
            error_log("Ошибка удаления таблицы blogs: " . $e->getMessage());
            throw $e;
        }
    }
}
