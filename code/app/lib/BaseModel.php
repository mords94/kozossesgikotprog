<?php

/**
 * Created by PhpStorm.
 * User: drava
 * Date: 2017. 03. 30.
 * Time: 3:38
 */
class BaseModel
{
    const RAW_CONNECTION_STRING = 'host=db port=5432 dbname=szte user=szte password=titok';

    protected $database;

    public function __construct()
    {
        $this->database = new Driver("pgsql", "db", "szte", "szte", "titok");
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


//        $stmt = $this->database->query("SELECT table_name
//                                   FROM information_schema.tables
//                                   WHERE table_schema= 'public'
//                                        AND table_type='BASE TABLE'
//                                   ORDER BY table_name");
        $tableList = [];
        foreach ($rows as $row) {
            $tableList[] = $row['table_name'];
        }

        return $tableList;
    }
}