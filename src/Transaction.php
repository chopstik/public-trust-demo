<?php

namespace App;

use Traits\HasSqliteDatabase;
use Traits\StringUtilities;
use Faker\Provider\Uuid;

class Transaction
{
    use HasSqliteDatabase, StringUtilities;

    private string $table = 'transactions';

    private string $jsonPath = 'transactions.json';

    public function __construct()
    {
        $this->initialiseDatabase($this->table);
    }

    protected function initialiseDatabase(string $table): void
    {
        // Create table if it doesn't yet exist
        $qry = 'CREATE TABLE IF NOT EXISTS ' . $table . ' (
            id INTEGER PRIMARY KEY, 
            transaction_id INTEGER, 
            transaction_uuid TEXT, 
            card_number TEXT, 
            iban TEXT, 
            transaction_detail TEXT,
            created DATETIME DEFAULT CURRENT_TIMESTAMP
            )';

        $result = $this->db()->querySingle($qry);

    }

    public function ingestJson(): array
    {
        $json = file_get_contents($this->jsonPath);
        $data = json_decode($json, true);

        // truncate data from the table to unnecessary data
        $this->db()->querySingle('DELETE FROM ' . $this->table);

        $transactions = [];

        foreach ($data as $transaction) {

            $transactionDetails = base64_encode(serialize($transaction['transaction']));

            $qry = 'INSERT INTO ' . $this->table . ' (
            transaction_id, 
            transaction_uuid, 
            card_number, 
            iban, 
            transaction_detail
            ) VALUES (
            ' . $transaction['id'] . ', 
            "' . Uuid::uuid() . '", 
            "' . $transaction['card_number'] . '", 
            "' . $transaction['IBAN'] . '", 
            "' . $transactionDetails . '"
            )';
            $this->db()->querySingle($qry);

            $result = $this->db()->querySingle($qry);

        }

        return $transactions;
    }

    public function getTransactions(): object
    {
        $transactions = [];
        $transactionRows = $this->db()->query('SELECT * FROM ' . $this->table);
        while ($row = $transactionRows->fetchArray(SQLITE3_ASSOC)) {
            $row['transaction_detail'] = unserialize(base64_decode($row['transaction_detail']));
            $transactions[] = (object)$row;
        }

        return (object)$transactions;
    }
}
