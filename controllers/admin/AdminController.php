<?php
session_start();

class AdminController {
    protected function authenticate() {
        if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] != 1) {
            header('Location: index.php?controller=authorization&action=login');
            exit;
        }
    }
}
?>
