<?php

/**
 * Created by PhpStorm.
 * User: drava
 * Date: 2017. 03. 30.
 * Time: 3:38
 */
class BaseModel
{
    const CONNECTION_STRING = 'host=db port=5432 dbname=szte user=szte password=titok';

    protected $connection;

    public function __construct() {
        $this->connection = new \PDO("pgsql:dbname=szte;host=db", 'szte', 'titok');
        $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }
}