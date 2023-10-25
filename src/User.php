<?php

namespace App;

use Faker\Factory as Fake;
use Traits\HasSqliteDatabase;
use Traits\StringUtilities;

/**
 * Please create some PHP code in a memory restricted environment to retrieve fictional user data from a database and
 * anonymize the email address. Please consider the use of generators or pagination and the use of SOLID.
 */
class User
{

    use HasSqliteDatabase, StringUtilities;

    private string $table = 'users';

    private int $testRows = 3;

    public function __construct()
    {
        $this->initialiseDatabase($this->table);
    }

    protected function initialiseDatabase(string $table): void
    {
        // Create table if it doesn't yet exist
        $this->db()->querySingle('CREATE TABLE IF NOT EXISTS ' . $table . ' (
            id INTEGER PRIMARY KEY, 
            name TEXT, 
            email TEXT, 
            address TEXT, 
            phone TEXT, 
            website TEXT, 
            company TEXT, 
            created DATETIME DEFAULT CURRENT_TIMESTAMP
            )');

    }

    public function createTestRecords(): void
    {
        $fake = Fake::create();

        // create an array of 5 fake users
        $users = [];
        for ($i = 0; $i < $this->testRows; $i++) {
            $users[] = [
                'name'    => $fake->name,
                'email'   => $fake->email,
                'address' => $fake->address,
                'phone'   => $fake->phoneNumber,
                'website' => $fake->url,
                'company' => $fake->company,
            ];
        }

        // truncate data from the table to unnecessary data
        $this->db()->querySingle('DELETE FROM ' . $this->table);

        // insert the users into the database
        foreach ($users as $user) {
            $this->db()->querySingle('INSERT INTO ' . $this->table . ' (name, email, address, phone, website, company) VALUES ("' . $user['name'] . '", "' . $user['email'] . '", "' . $user['address'] . '", "' . $user['phone'] . '", "' . $user['website'] . '", "' . $user['company'] . '")');
        }

    }

    public function getUser(int $id, bool $obfuscate = false): object
    {
        $user = $this->db()->querySingle('SELECT * FROM ' . $this->table . ' WHERE id = ' . $id)->fetchArray(SQLITE3_ASSOC);
        if ($obfuscate)
            $user['email'] = $obfuscate ? $this->anonymizeEmail($user['email']) : $user['email'];

        return (object)$user;

    }

    public function getUsers(bool $obfuscate = false, int $limit = 3, int $offset = 0): object
    {
        $users = [];
        $userRows = $this->db()->query('SELECT * FROM ' . $this->table);
        while ($row = $userRows->fetchArray(SQLITE3_ASSOC)) {
            if ($obfuscate)
                $row['email'] = $obfuscate ? $this->anonymizeEmail($row['email']) : $row['email'];

            $users[] = (object)$row;
        }

        return (object)$users;
    }

}
