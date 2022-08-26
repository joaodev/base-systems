<?php

namespace App\Model;

use Core\Db\Model;

class Politics extends Model
{
    public function __construct()
    {
        $this->setTable('p_privacy');
    }

    public function getOne()
    {
        try {
            $query = "
                SELECT uuid, description
                FROM {$this->getTable()}
                ORDER BY id LIMIT 1
            ";

            $stmt = $this->openDb()->query($query);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);

            $stmt = null;
            $this->closeDb();

            return $result;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}