<?php

namespace App\Utils\Helpers;

class ModelHelper
{
    public function filterAllColumns(&$model, $filters, $rules, $columnsEncrypted)
    {
        foreach ($filters as $key => $value) {

            //Pesquisar colunas com has MD5
            if (in_array($key, array_keys($columnsEncrypted))) {

                $columnName = $columnsEncrypted[$key];
                $model->where($columnName, hash('md5', $value));

            //Apenas colunas que esteja nas regras de validação
            } else if (in_array($key, array_keys($rules))) {

                if (is_array($value) && count($value) === 2) {

                    $model->whereBetween($key, $value);
                    
                } else {
                    $param = json_decode($value);
                    if (json_last_error() == JSON_ERROR_NONE && is_object($param)) {
                        $filterColumns = is_array($param) ? $param : [$param];

                        foreach ($filterColumns as $filterColumn) {
                            $value = $filterColumn->value;
                            $operator = empty($filterColumn->operator)  ? '=' : $filterColumn->operator;

                            $model->where($key, $operator, $value);
                        }
                    } else {
                        $model->where($key, $value);
                    }
                }
            }
        }

        return $model;
    }
}
