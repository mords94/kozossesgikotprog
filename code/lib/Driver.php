<?php
namespace Library;

use \PDO;

const SAFE_DELETE = false;

class Driver extends PDO
{
    private $delimiter = '';

    private $lastSql = '';

    function __construct($DB_TYPE, $DB_HOST, $DB_NAME, $DB_USER, $DB_PASS)
    {

        $s = ["pgsql:dbname=szte;host=db", 'szte', 'titok'];
        $this->dbname = $DB_NAME;

        $connectionString = [
            "mysql" => $DB_TYPE . ":host=" . $DB_HOST . ";dbname=" . $DB_NAME,
            "pgsql" => "$DB_TYPE:dbname=$DB_NAME;host=$DB_HOST",
        ];
        parent::__construct($connectionString[$DB_TYPE], $DB_USER, $DB_PASS);
        $this->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if ($DB_TYPE == "mysql") {
            $setCharset = $this->prepare("SET CHARACTER SET " . DB_CHARSET);
            $setCollation = $this->prepare("SET COLLATION_CONNECTION='" . DB_COLLATION . "'");
            $setCharset->execute();
            $setCollation->execute();
            $this->convertEncoding();
        }
    }

    public function getLastSql() {
        return $this->lastSql;
    }

    private function convertEncoding()
    {
        $tables = $this->selectCustom("SHOW TABLES");
        foreach ($tables as $table) {
            $table = $table['Tables_in_' . $this->dbname];
            $this->lastSql = $sql = "ALTER TABLE $table CHARACTER SET
                    utf8 COLLATE utf8_general_ci;";
            $sqlother = "ALTER TABLE $table
                    CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;";
            $this->prepare($sql)->execute();
            $this->prepare($sqlother)->execute();
        }
    }

    private function _printerr($e)
    {
        echo "<br /><span style='color:#DF0101;'>" . $e . "</span>\n";
    }

    public function insert($table, $data, $debug = false)
    {
        ksort($data);

        $fieldNames = "$this->delimiter" . implode("$this->delimiter, $this->delimiter", array_keys($data)) . "$this->delimiter";
        $fieldValues = ":" . implode(", :", array_keys($data)) . "";

        $this->lastSql = $sql = "INSERT INTO $table ($fieldNames) VALUES ($fieldValues)";
        $sth = $this->prepare($sql);
        if ($debug) {
            $this->_printerr($sth->queryString);
        }
        foreach ($data as $key => $value) {
            $sth->bindValue(":$key", $value);
        }

        return $sth->execute();


    }

    public function lastID()
    {
        return $this->lastInsertId();
    }

    public function update($table, $data, $where, $debug = false)
    {
        ksort($data);
        $fieldDetails = null;
        foreach ($data as $key => $value) {
            $fieldDetails .= "$this->delimiter$key$this->delimiter = :$key,";
        }

        $fieldDetails = rtrim($fieldDetails, ',');
        $this->lastSql = $sql = "UPDATE $table SET $fieldDetails WHERE $where";
        if ($debug) echo $sql;
        $sth = $this->prepare($sql);

        foreach ($data as $key => $value) {
            $sth->bindValue(":$key", $value);
        }

        return $sth->execute();
    }

    public function colExists($table, $col)
    {
        $this->lastSql = $sql = "SELECT * FROM information_schema.COLUMNS
                WHERE TABLE_NAME = '$table' AND COLUMN_NAME = '$col';";
        $sth = $this->prepare($sql);
        $sth->execute();

        return $sth->rowCount() > 0 ? true : false;
    }

    public function delete($table, $where, $debug = false, $safe = SAFE_DELETE)
    {
        if ($safe) {
            if ($this->colExists($table, "deleted")) {
                return $this->update($table, ["deleted" => 1], $where, $debug);
            } else return false;
        } else {
            $this->lastSql = $sql = "DELETE FROM $table WHERE $where";
            if ($debug) echo $sql;
            $sth = $this->prepare($sql);

            return $sth->execute();
        }

    }

    public function fetchOne($sql, $debug = false)
    {
        if ($debug) echo $sql;

        $sth = $this->prepare($sql);
        $sth->execute();
        $result = $sth->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

    public function fetchAll($sql, $debug = false)
    {
        if ($debug) echo $sql;

        $sth = $this->prepare($sql);
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function selectFrom($table, $order = "", $debug = false)
    {
        $order = isset($order) && $order != "" ? "ORDER BY " . $order : "";
        $this->lastSql = $sql = "SELECT * FROM $this->delimiter" . $table . "$this->delimiter" . $order . ";";
        $sth = $this->prepare($sql);
        if ($debug) echo $sql;
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function selectOneFrom($table, $order = "")
    {
        $order = isset($order) && $order != "" ? "ORDER BY $this->delimiter" . $order . "$this->delimiter" : "";
        $sth = $this->prepare("SELECT * FROM $this->delimiter" . $table . "$this->delimiter" . $order . ";");
        $sth->execute();
        $result = $sth->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

    public function selectFromWhere($table, $where, $order = "", $debug = false)
    {
        $order = isset($order) && $order != "" ? "ORDER BY " . $order : "";

        if (is_array($where)) {
            $conditions = $where;
            $where = [];
            foreach ($conditions as $cell => $value) {
                $where[] = "$cell = '$value'";
            }

            $where = implode(" AND ", $where);
        }

        $this->lastSql = $sql = "SELECT * FROM $table WHERE $where $order";
        if ($debug) echo $sql;

        $sth = $this->prepare($sql);
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function selectOneFromWhere($table, $where, $order = null, $debug = false)
    {
        $result = null;
        $order = isset($order) && $order != "" ? "ORDER BY " . $order : "";
        $this->lastSql = $sql = "SELECT * FROM $table WHERE $where $order";
        if ($debug) echo $sql;
        $sth = $this->prepare($sql);
        $sth->execute();
        $result = $sth->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

    public function numRows($table, $debug = false)
    {
        $result = null;
        $this->lastSql = $sql = "SELECT COUNT(*) AS 'count' FROM $table";
        if ($debug) echo $sql;
        $sth = $this->prepare($sql);
        $sth->execute();
        $result = $sth->fetch(PDO::FETCH_ASSOC);

        return $result['count'];
    }

    public function selectCustom($sql, $debug = false)
    {
        if ($debug) echo $sql;
        $this->lastSql = $sql;
        $sth = $this->prepare($sql);

        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function selectOneCustom($sql, $debug = false)
    {
        if ($debug) echo $sql;
        $this->lastSql = $sql;
        $sth = $this->prepare($sql);

        $sth->execute();
        $result = $sth->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

}
