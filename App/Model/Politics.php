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
                SELECT *
                FROM {$this->getTable()}
                WHERE id = :id
            ";

            $stmt = $this->openDb()->prepare($query);
            $stmt->bindValue(":id", 1);
            $stmt->execute();

            $result = $stmt->fetch(\PDO::FETCH_ASSOC);

            $stmt = null;
            $this->closeDb();

            return $result;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}