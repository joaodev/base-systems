<?php

namespace Core\Db;

class InitDb
{
    public $db;
    protected $table;

    public function getTable()
    {
        return $this->table;
    }

    public function setTable($table)
    {
        $this->table = $table;
    }

    public function openDb()
    {
        return $this->db = \Core\Init\Bootstrap::getDb();
    }

    public function closeDb()
    {
        $this->db = null;
    }
}