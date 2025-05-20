<?php
require_once 'CustomFormValidation.php';

class ResultsVerification extends CustomFormValidation {
    private $score = 0;
    private $total_questions = 3;

    public function validate($post_array) {
        parent::validate($post_array);
        $this->score = 0;

        if (isset($post_array['question1']) && $post_array['question1'] === '4') $this->score++;
        if (isset($post_array['question2']) && $post_array['question2'] === 'moscow') $this->score++;
        if (isset($post_array['question3']) && $post_array['question3'] === '25') $this->score++;

        if (isset($post_array['question1']) && $post_array['question1'] !== '4') {
            $this->errors['question1'] = "Неверный ответ на вопрос 1";
        }
        if (isset($post_array['question2']) && $post_array['question2'] !== 'moscow') {
            $this->errors['question2'] = "Неверный ответ на вопрос 2";
        }
        if (isset($post_array['question3']) && $post_array['question3'] !== '25') {
            $this->errors['question3'] = "Неверный ответ на вопрос 3";
        }

        return empty($this->errors);
    }

    public function showResults() {
        return "<p>Ваш результат: $this->score из $this->total_questions</p>";
    }
}
?>