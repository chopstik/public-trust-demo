<?php

namespace Traits;

use SQLite3;

trait HasSqliteDatabase
{
    private $schema = 'demo';

    public function db(): SQLite3
    {
        return $this->getSqliteDriver();
    }

    protected function getSqliteDriver(): SQLite3
    {
        return new SQLite3("{$this->schema}.sqlite");
    }


}
