<?php
namespace Library;

class BaseModel
{
    const RAW_CONNECTION_STRING = 'host=db port=5432 dbname=szte user=szte password=titok';

    protected $database;

    public function __construct()
    {

        $host = preg_match('/localhost:\d{2,5}/', $_SERVER['HTTP_HOST']) ? 'db' : 'localhost';
        $this->database = new Driver("pgsql", $host, "szte", "szte", "titok");
        $this->database->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function getDatabase()
    {
        return $this->database;
    }

    public function getTables($debug = false)
    {
        $rows = $this->database->selectFromWhere(
            'information_schema.tables ',
            "table_schema = 'public' AND table_type LIKE 'BASE_TABLE'",
            'table_name',
            $debug
        );

        $tableList = [];
        foreach ($rows as $row) {
            $tableList[] = $row['table_name'];
        }

        return $tableList;
    }
}