<?php

/**
 * Created by PhpStorm.
 * User: drava
 * Date: 2017. 03. 30.
 * Time: 1:44
 */
class Model extends BaseModel
{
    public function createTables()
    {
        $sqlList = [
            'CREATE TABLE IF NOT EXISTS stocks (
                        id serial PRIMARY KEY,
                        symbol character varying(10) NOT NULL UNIQUE,
                        company character varying(255) NOT NULL UNIQUE 
                     )',
            'CREATE TABLE IF NOT EXISTS stock_valuations (
                        stock_id INTEGER NOT NULL,
                        value_on date NOT NULL,
                        price numeric(8,2) NOT NULL DEFAULT 0,
                        PRIMARY KEY (stock_id, value_on),
                        FOREIGN KEY (stock_id) REFERENCES stocks(id)
                    );',
        ];

        // execute each sql statement to create new tables
        foreach ($sqlList as $sql) {
            $this->connection->exec($sql);
        }

        return $this;
    }

    public function getTables()
    {
        $stmt = $this->connection->query("SELECT table_name 
                                   FROM information_schema.tables 
                                   WHERE table_schema= 'public' 
                                        AND table_type='BASE TABLE'
                                   ORDER BY table_name");
        $tableList = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $tableList[] = $row['table_name'];
        }

        return $tableList;
    }

    public function insertStock($symbol, $company) {
        // prepare statement for insert
        $sql = 'INSERT INTO stocks(symbol,company) VALUES(:symbol,:company)';
        $stmt = $this->pdo->prepare($sql);

        // pass values to the statement
        $stmt->bindValue(':symbol', $symbol);
        $stmt->bindValue(':company', $company);

        // execute the insert statement
        $stmt->execute();

        // return generated id
        return $this->connection->lastInsertId('stocks_id_seq');
    }

    /**
     * Insert multiple stocks into the stocks table
     * @param array $stocks
     * @return a list of inserted ID
     */
    public function insertStockList($stocks) {
        $sql = 'INSERT INTO stocks(symbol,company) VALUES(:symbol,:company)';
        $stmt = $this->pdo->prepare($sql);

        $idList = [];
        foreach ($stocks as $stock) {
            $stmt->bindValue(':symbol', $stock['symbol']);
            $stmt->bindValue(':company', $stock['company']);
            $stmt->execute();
            $idList[] = $this->connection->lastInsertId('stocks_id_seq');
        }
        return $idList;
    }

    public function updateStock($id, $symbol, $company) {

        // sql statement to update a row in the stock table
        $sql = 'UPDATE stocks '
            . 'SET company = :company, '
            . 'symbol = :symbol '
            . 'WHERE id = :id';

        $stmt = $this->connection->prepare($sql);

        // bind values to the statement
        $stmt->bindValue(':symbol', $symbol);
        $stmt->bindValue(':company', $company);
        $stmt->bindValue(':id', $id);
        // update data in the database
        $stmt->execute();

        // return the number of row affected
        return $stmt->rowCount();
    }
}