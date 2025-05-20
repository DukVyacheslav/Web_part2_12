<?php
require_once 'FormValidation.php';

class CustomFormValidation extends FormValidation {
    public function isValidAnswer($data, $correct_answer) {
        return $data !== $correct_answer ? "Неверный ответ" : null;
    }

    public function validateTestAnswers($data) {
        // Add your validation logic for test answers here
        return true; // Assuming validation passes
    }    
}
?>