<?php

class Model{
    protected static $tableName = '';
    protected static $columns = [];
    protected $values = [];

    function __construct($arr, $sanitize = true){
        $this->loadFromArray($arr, $sanitize);
    }

    public function loadFromArray($arr, $sanitize = true){
        if ($arr) {
            $conn = Database::getConnection();
            foreach ($arr as $key => $value) {
                $cleanValue = strip_tags(trim($cleanValue));
                if ($sanitize && isset($cleanValue)){
                    $cleanValue = $value;
                    $cleanValue = htmlentities($cleanValue, ENT_NOQUOTES);
                    $cleanValue =mysqli_real_escape_string($conn, $cleanValue);
                }
                $this->$key = $cleanValue;
            }
            $conn->close();
        }
    }

    public function __get($key){
        return $this->values[$key];
    }

    public function __set($key, $value){
        $this->values[$key] = $value;
    }

    public static function getOne($filters = [], $colums = '*'){
        $class = get_called_class();
        $result = static::getResultSetFromSelect($filters, $colums);
        return $result ? new $class($result->fetch_assoc()) : null;
    }

    public static function get($filters = [], $columns = '*'){
        $objects = [];
        $result = static::getResultSetFromSelect($filters, $columns);
        if ($result) {
            $class = get_called_class();
            while ($row = $result->fetch_assoc()) {
                array_push($objects, new $class($row));
            }
        }
        return $objects;
    }

    public static function getResultSetFromSelect($filters = [], $columns = '*'){
        $sql = "select {$columns} from "
            . static::$tableName
            . static::getFilters($filters);
        $result = Database::getResultFromQuery($sql);
        if ($result->num_rows === 0) {
            return null;
        } else {
            return $result;
        }
    }

    public function insert(){
        $sql = "insert into " . static::$tableName . "("
            . implode(",", static::$columns) . ") values (";
        foreach (static::$columns as $col) {
            $sql .= static::getFormatedValue($this->$col) . ",";
        }
        $sql[strlen($sql) - 1]  = ')' ;
        $id = Database::executeSQL($sql);
        $this->id = $id;
    }

    public function update(){
        $sql = "update " . static::$tableName . " set ";
        foreach (static::$columns as $col){
            $sql .= " ${col} = " .static::getFormatedValue($this->$col) . ",";
        }
        $sql[strlen($sql) - 1]  = ' ';
        $sql .= "where id = {$this->id}";
        Database::executeSQL($sql);
    }

    public static function getCount($filters = []){
        $result = static::getResultSetFromSelect(
            $filters, 'count(*) as count');
        return $result->fetch_assoc()['count'];
    }

    public function delete(){
        static::deleteById($this->id);
    }

    public static function deleteById($id){
        $sql = "delete from " . static::$tableName . " where id = {$id}";
        Database::executeSQL($sql);
    }

    private static function getFilters($filters){
        $sql = '';
        if (count($filters) > 0) {
            $sql .= " where 1 = 1";
            foreach ($filters as $column => $value) {
                if($column == 'raw'){
                    $sql .= " AND {$value}";
                } else {
                    $sql .= " AND ${column} = " . static::getFormatedValue($value);
                }
            }
        }
        return $sql;
    }

    private static function getFormatedValue($value){
        if (is_null($value)) {
            return "null";
        }
        elseif (gettype($value) === 'string') {
            return "'${value}'";
        } else {
            return $value;
        }
    }


}