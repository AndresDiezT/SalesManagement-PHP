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
                if (strpos($rule, "unique:") === 0 && $database) {
                    $cleanRule = str_replace("unique:", "", $rule);
                    list($table, $column, $idColumn, $excludeId) = array_pad(explode(",", $cleanRule), 4, null);

                    $table = trim($table);
                    $column = trim($column);
                    $idColumn = trim($idColumn);
                    $excludeId = $excludeId !== null ? trim($excludeId) : null;

                    if (self::existsInDatabase($database, $table, $column, $data[$field], $idColumn, $excludeId)) {
                        $errors[$field] = "El $column ya está en uso";
                    }
                }
            }
        }

        return $errors;
    }

    private static function existsInDatabase($database, $table, $column, $value, $idColumn = "", $excludeId = null)
    {
        if (empty($table) || empty($column)) {
            return false;
        }

        $query = "SELECT COUNT(*) FROM $table WHERE $column = :value";

        if (!empty($idColumn) && $excludeId !== null) {
            $query .= " AND `$idColumn` != :excludeId";
        }

        $statement = $database->prepare($query);
        $statement->bindParam(':value', $value);

        if (!empty($idColumn) && $excludeId !== null) {
            $statement->bindParam(':excludeId', $excludeId);
        }

        $statement->execute();
        return $statement->fetchColumn() > 0;
    }
}
