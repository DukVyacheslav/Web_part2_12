<?php
class FormValidation {
    private $rules = [];
    public $errors = [];

    public function setRule($field_name, $validator_name, $params = []) {
        $this->rules[$field_name] = [
            'validator' => $validator_name,
            'params' => $params
        ];
    }

    public function isNotEmpty($data) {
        if (is_array($data)) {
            return empty($data) ? "Поле не должно быть пустым" : null;
        }
        return empty(trim($data)) ? "Поле не должно быть пустым" : null;
    }

    public function isInteger($data) {
        return !filter_var($data, FILTER_VALIDATE_INT) ? "Значение должно быть целым числом" : null;
    }

    public function isEmail($data) {
        return !filter_var($data, FILTER_VALIDATE_EMAIL) ? "Некорректный email" : null;
    }

    public function isPhone($data) {
        $phone = preg_replace('/[^0-9]/', '', $data);
        return strlen($phone) < 10 ? "Некорректный номер телефона" : null;
    }

    public function isDate($data) {
        $date = date_parse($data);
        return $date['error_count'] > 0 ? "Некорректная дата" : null;
    }

    public function minLength($data, $length) {
        return mb_strlen($data) < $length ? "Минимальная длина - $length символов" : null;
    }

    public function maxLength($data, $length) {
        return mb_strlen($data) > $length ? "Максимальная длина - $length символов" : null;
    }

    public function inRange($data, $min, $max) {
        $num = filter_var($data, FILTER_VALIDATE_INT);
        return ($num === false || $num < $min || $num > $max) ? "Значение должно быть между $min и $max" : null;
    }

    public function inList($data, $list) {
        return !in_array($data, $list) ? "Недопустимое значение" : null;
    }

    public function validate($post_array) {
        $this->errors = [];
        foreach ($this->rules as $field => $rule) {
            if (!isset($post_array[$field])) {
                $this->errors[$field] = "Поле отсутствует";
                continue;
            }

            $data = $post_array[$field];
            $error = null;

            switch ($rule['validator']) {
                case 'required':
                case 'notEmpty':
                    $error = $this->isNotEmpty($data);
                    break;
                case 'integer':
                    $error = $this->isInteger($data);
                    break;
                case 'email':
                    $error = $this->isEmail($data);
                    break;
                case 'phone':
                    $error = $this->isPhone($data);
                    break;
                case 'date':
                    $error = $this->isDate($data);
                    break;
                case 'minLength':
                    $error = $this->minLength($data, $rule['params']['length']);
                    break;
                case 'maxLength':
                    $error = $this->maxLength($data, $rule['params']['length']);
                    break;
                case 'range':
                    $error = $this->inRange($data, $rule['params']['min'], $rule['params']['max']);
                    break;
                case 'list':
                    $error = $this->inList($data, $rule['params']['values']);
                    break;
            }

            if ($error) {
                $this->errors[$field] = $error;
            }
        }
        return empty($this->errors);
    }

    public function getErrors() {
        return $this->errors;
    }

    public function hasErrors() {
        return !empty($this->errors);
    }

    public function showErrors() {
        if (empty($this->errors)) {
            return '';
        }
        
        $html = '<div class="alert alert-danger"><ul class="mb-0">';
        foreach ($this->errors as $field => $error) {
            $html .= sprintf(
                '<li>%s: %s</li>', 
                htmlspecialchars(ucfirst($field)), 
                htmlspecialchars($error)
            );
        }
        $html .= '</ul></div>';
        return $html;
    }
}
?>