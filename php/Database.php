<?php

include './config.php';

class Database {

    protected static $db;
    private $_connection;
    private $server = Constans::SERVER;
    private $dbuser = Constans::DBUSER;
    private $dbpass = Constans::DBPASS;
    private $dbdatabase = Constans::DBDATABASE;
    private $error = '';

    /**
     *
     * @return type
     */
    public function getMsg() {
        return $this->error;
    }

    /**
     *
     * @param type $err
     */
    public function setError($err) {
        $this->error = err;
    }

    /**
     *
     */
    private function __construct() {
        try {
            $this->_connection = new PDO("mysql:host={$this->server};dbname={$this->dbdatabase}", $this->dbuser, $this->dbpass);
            $this->_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection Error: " . $e->getMessage();
        }
    }

    /**
     *
     * @return type
     */
    public static function getConnection() {
        if (!self::$db) {
            self::$db = new self();
        }
        return self::$db;
    }

    /**
     *
     * @param type $columns
     * @return string
     */
    private function queryColumns($columns) {

        if (!isset($columns) || $columns == "*") {
            return "*";
        }

        if (isset($columns) && !is_array($columns) && $columns != "*") {
            return $columns;
        } else {

            $formatted_str = "";

            foreach ($columns as $column) {
                $formatted_str .= $column . ",";
            }
        }

        return substr($formatted_str, 0, -1);
    }

    /**
     *
     * @param type $columns
     * @return type
     */
    private function prepareInsertion($columns) {
        $formatted_str = "";

        foreach ($columns as $column) {
            $formatted_str .= ":" . $column . ",";
        }

        return substr($formatted_str, 0, -1);
    }

    /**
     *
     * @param type $cols
     * @param type $values
     * @return type
     */
    private function prepareSetQuery($cols, $values) {

        $formatted_str = "";

        for ($i = 0; $i < sizeof($values); $i++) {
            $formatted_str .= $cols[$i] . "=\"" . $values[$i] . "\",";
        }

        return substr($formatted_str, 0, -1);
    }

    /**
     *
     * @param type $q
     * @param type $columns
     * @param type $values
     * @return type
     */
    private function executionArray($q, $columns, $values) {
        $formatted_str = "";

        for ($i = 0; $i < sizeof($columns); $i++) {
//            $formatted_str .= "':".$columns[$i] . "' => " . $values[$i] . ",";
            $q->bindParam(":" . $columns[$i], $values[$i], PDO::PARAM_STR, 100);
        }

        return substr($formatted_str, 0, -1);
    }

    /**
     *
     * @param type $cols
     * @param type $table
     * @param type $query_conditional
     * @return type
     */
    public function select($cols, $table, $query_conditional) {
        try {
            if (!isset($query_conditional)) {
                $query = "SELECT " . $this->queryColumns($cols) . " FROM " . $table;
            } else {
                $query = "SELECT " . $this->queryColumns($cols) . " FROM " . $table . " " . $query_conditional;
            }
            $q = $this->_connection->prepare($query);
            $q->execute();
            $row = $q->fetch();
            return $row;
        } catch (Exception $e) {
            $this->error = $e->getMessage();
        }
    }

    public function selectJoin($cols, $table, $join, $query_conditions) {
        try {
            $result = array();
            $query = "SELECT " . $this->queryColumns($cols) . " FROM " . $table . " " . $join . " " . $query_conditions;
            $this->_connection->prepare($query);
            $q = $this->_connection->query($query);
            $q->setFetchMode(PDO::FETCH_ASSOC);
            while ($row = $q->fetch()) {
                array_push($result, $row);
            }
            return $result;
        } catch (Exception $e) {
            $this->error = $e->getMessage();
        }
    }

    /**
     *
     * @param type $cols
     * @param type $table
     * @param type $query_conditional
     * @return type
     */
    public function selectAll($table, $query_conditional) {
        try {
            if (!isset($query_conditional)) {
                $query = "SELECT * FROM " . $table;
            } else {
                $query = "SELECT * FROM " . $table . " " . $query_conditional;
            }
            $q = $this->_connection->prepare($query);
            $q->execute();
            $row = $q->fetch();
            return $row;
        } catch (Exception $e) {
            $this->error = $e->getMessage();
        }
    }

    /**
     *
     * @param type $cols
     * @param type $table
     * @param type $query_conditional
     * @return array
     */
    public function selectList($cols, $table, $query_conditional) {
        try {
            $result = array();
            if (!isset($query_conditional)) {
                $query = "SELECT " . $this->queryColumns($cols) . " FROM " . $table;
            } else {
                $query = "SELECT " . $this->queryColumns($cols) . " FROM " . $table . " " . $query_conditional;
            }
            $this->_connection->prepare($query);
            $q = $this->_connection->query($query);
            $q->setFetchMode(PDO::FETCH_ASSOC);
            while ($row = $q->fetch()) {
                array_push($result, $row);
            }
            return $result;
        } catch (Exception $e) {
            $this->error = $e->getMessage();
        }
    }

    public function selectListDistinct($cols, $table, $query_conditional) {
        try {
            $result = array();
            if (!isset($query_conditional)) {
                $query = "SELECT DISTINCT " . $this->queryColumns($cols) . " FROM " . $table;
            } else {
                $query = "SELECT DISTINCT " . $this->queryColumns($cols) . " FROM " . $table . " " . $query_conditional;
            }
            $this->_connection->prepare($query);
            $q = $this->_connection->query($query);
            $q->setFetchMode(PDO::FETCH_ASSOC);
            while ($row = $q->fetch()) {
                array_push($result, $row);
            }
            return $result;
        } catch (Exception $e) {
            $this->error = $e->getMessage();
        }
    }

    /**
     *
     * @param type $cols
     * @param type $table
     * @param type $query_conditional
     * @return array
     */
    public function selectListAll($table, $query_conditional) {
        try {
            $result = array();
            if (!isset($query_conditional)) {
                $query = "SELECT * FROM " . $table;
            } else {
                $query = "SELECT * FROM " . $table . " " . $query_conditional;
            }
            $this->_connection->prepare($query);
            $q = $this->_connection->query($query);
            $q->setFetchMode(PDO::FETCH_ASSOC);
            while ($row = $q->fetch()) {
                array_push($result, $row);
            }
            return $result;
        } catch (Exception $e) {
            $this->error = $e->getMessage();
        }
    }

    /**
     *
     * @param type $cols
     * @param type $values
     * @param type $table
     * @param type $query_conditional
     * @return boolean
     */
    public function insertData($cols, $values, $table, $query_conditional) {
        try {
            if (!isset($query_conditional)) {
                $query = "INSERT INTO " . $table . " (" . $this->queryColumns($cols) . ") VALUES (" . $this->prepareInsertion($cols) . ") ";
            } else {
                $query = "INSERT INTO " . $table . " (" . $this->queryColumns($cols) . ") VALUES (" . $this->prepareInsertion($cols) . ") " . $query_conditional;
            }
            $q = $this->_connection->prepare($query);

            $this->executionArray($q, $cols, $values);

            if ($q->execute()) {
                return true;
            }
            return $q->errorCode();
        } catch (Exception $e) {
            $this->error = $e->getMessage();
        }
    }

    /**
     *
     * @param type $statement
     * @return type
     */
    public function getRowsCount($statement) {
        return $this->prepare($statement)->rowCount();
    }

    /**
     *
     * @param type $cols
     * @param type $values
     * @param type $keys
     * @param type $table
     * @param type $query_conditional
     * @return boolean
     */
    public function updateData($cols, $values, $keys, $table, $query_conditional) {
        try {


            if (isset($set_conditional) && isset($query_conditional)) {
                $query = "UPDATE " . $table . " SET " . $this->prepareSetQuery($cols, $values);
            } else {
                $query = "UPDATE " . $table . " SET " . $this->prepareSetQuery($cols, $values) . " " . $query_conditional;
            }

            $q = $this->_connection->prepare($query);

            if ($q->execute()) {
                return true;
            }

            return false;
        } catch (Exception $e) {
            $this->error = $e->getMessage();
        }
    }

    /**
     *
     * @param type $statement
     * @return type
     */
    public function prepare($statement) {
        $q = $this->_connection->prepare($statement);
        $q->execute();
        return $q;
    }

    /**
     *
     * @param type $statement
     * @return type
     */
    public function getRowsNum($statement) {
        return $this->prepare($statement)->fetchColumn();
    }

    /**
     *
     * @param type $table
     * @param type $cols
     * @param type $query_conditional
     * @return boolean
     */
    public function deleteWithPictures($table, $cols, $query_conditional) {
        $temp = array();
        $temp = $this->selectList($cols, $table, $query_conditional);
        foreach ($temp as $key => $val) {
            if ($val['picture'] != NULL) {
                unlink('../images/coll/' . $val['picture']);
            }
        }
        try {
            if (!isset($query_conditional)) {
                $query = "Delete from " . $table . "";
            } else {
                $query = "Delete from " . $table . " " . $query_conditional;
            }
            $q = $this->_connection->prepare($query);
            if ($q->execute()) {
                return true;
            }
            return $q->errorCode();
        } catch (Exception $e) {
            $this->error = $e->getMessage();
        }
    }

    public function delete($table, $query_conditional) {
        try {
            if (!isset($query_conditional)) {
                $query = "Delete from " . $table . "";
            } else {
                $query = "Delete from " . $table . " " . $query_conditional;
            }
            $q = $this->_connection->prepare($query);
            if ($q->execute()) {
                return true;
            }
            return $q->errorCode();
        } catch (Exception $e) {
            $this->error = $e->getMessage();
        }
    }

    /**
     * Magic method clone is empty to prevent duplication of connection
     */
    private function __clone() {
        throw new Exception("Can't clone a singleton");
    }

}

?>
