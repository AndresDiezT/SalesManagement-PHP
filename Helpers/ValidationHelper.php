<?php
class ValidationHelper
{
    public static function validate($data, $rules, $database = null)
    {
        $errors = [];

        foreach ($rules as $field => $validations) {
            foreach ($validations as $rule) {
                if ($rule === "required" && empty($data[$field])) {
                    $errors[$field] = "El campo $field es obligatorio";
                }
                if ($rule === "email" && !filter_var($data[$field], FILTER_VALIDATE_EMAIL)) {
                    $errors[$field] = "El campo $field debe ser un correo válido";
                }
                if ($rule === "text" && !preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/u", $data[$field])) {
                    $errors[$field] = "El campo $field solo debe contener letras y espacios";
                }
                if ($rule === "numeric" && !is_numeric($data[$field])) {
                    $errors[$field] = "El campo $field debe ser numerico";
                }
                if (strpos($rule, "min:") === 0) {
                    $min = (int) explode(":", $rule)[1];
                    if (strlen($data[$field]) < $min) {
                        $errors[$field] = "El campo $field debe tener al menos $min caracteres";
                    }
                }
                if (strpos($rule, "max:") === 0) {
                    $max = (int) explode(":", $rule)[1];
                    if (strlen($data[$field]) > $max) {
                        $errors[$field] = "El campo $field no debe exceder los $max caracteres";
                    }
                }
                if (strpos($rule, "match:") === 0) {
                    $matchField = explode(":", $rule)[1];
                    if ($data[$field] !== $data[$matchField]) {
                        $errors[$field] = "Las contraseñas no coinciden";
                    }
                }
            }
        }

        return $errors;
    }
}
